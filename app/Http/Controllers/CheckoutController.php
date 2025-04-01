<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingZone;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        $items = [];
        $subtotal = 0;
        $totalWeight = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'customizations' => $details['customizations'] ?? [],
                    'subtotal' => $product->price * $details['quantity']
                ];
                $subtotal += $product->price * $details['quantity'];
                $totalWeight += $product->weight * $details['quantity'];
            }
        }
        
        $user = auth()->user();
        $addresses = $user ? $user->addresses : [];
        $shippingZones = ShippingZone::all();
        
        return view('checkout.index', compact(
            'items',
            'subtotal',
            'totalWeight',
            'addresses',
            'shippingZones'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate order totals
            $subtotal = 0;
            $totalWeight = 0;
            $items = [];
            
            foreach ($cart as $id => $details) {
                $product = Product::findOrFail($id);
                
                // Check stock
                if ($product->stock < $details['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                
                // Calculate item price with customizations
                $price = $product->price;
                if (!empty($details['customizations'])) {
                    foreach ($details['customizations'] as $optionId) {
                        $option = $product->customizationOptions()->find($optionId);
                        if ($option) {
                            $price += $option->price_adjustment;
                        }
                    }
                }
                
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'price' => $price,
                    'customizations' => $details['customizations'] ?? []
                ];
                
                $subtotal += $price * $details['quantity'];
                $totalWeight += $product->weight * $details['quantity'];
                
                // Update stock
                $product->decrement('stock', $details['quantity']);
            }
            
            // Get shipping cost
            $shippingZone = ShippingZone::findForCountry(
                Address::find($request->shipping_address_id)->country
            );
            
            if (!$shippingZone) {
                throw new \Exception('Shipping not available for this address.');
            }
            
            $shippingCost = $shippingZone->calculateShipping($totalWeight);
            
            // Calculate total
            $total = $subtotal + $shippingCost;
            
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);
            
            // Create order items
            foreach ($items as $item) {
                $order->items()->create($item);
            }
            
            // Clear cart
            session()->forget('cart');
            
            DB::commit();
            
            // Redirect to payment gateway or order confirmation
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'order_id' => $order->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['items.product', 'shippingAddress', 'billingAddress']);
        
        return view('checkout.show', compact('order'));
    }
    
    public function paymentSuccess(Order $order)
    {
        $this->authorize('view', $order);
        
        // Update order status
        $order->update(['status' => 'paid']);
        
        // Send confirmation email
        $order->user->notify(new OrderConfirmation($order));
        
        return view('checkout.success', compact('order'));
    }
    
    public function paymentCancel(Order $order)
    {
        $this->authorize('view', $order);
        
        // Restore product stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }
        
        // Delete order
        $order->delete();
        
        return redirect()->route('cart.index')
            ->with('error', 'Order was cancelled.');
    }
    
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'weight' => 'required|numeric|min:0'
        ]);
        
        $address = Address::findOrFail($request->address_id);
        $shippingZone = ShippingZone::findForCountry($address->country);
        
        if (!$shippingZone) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping not available for this address.'
            ], 422);
        }
        
        $shippingCost = $shippingZone->calculateShipping($request->weight);
        
        return response()->json([
            'success' => true,
            'shipping_cost' => $shippingCost,
            'estimated_days' => $shippingZone->estimated_days
        ]);
    }
} 