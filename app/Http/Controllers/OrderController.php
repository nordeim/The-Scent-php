<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress', 'billingAddress']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $order->status;
            $order->update([
                'status' => $request->status,
                'tracking_number' => $request->tracking_number,
                'notes' => $request->notes
            ]);
            
            // Update product stock if order is cancelled
            if ($oldStatus !== 'cancelled' && $request->status === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->increment('stock', $item->quantity);
                }
            }
            
            // Send status update email
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            
            DB::commit();
            
            return back()->with('success', 'Order status updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the order status.');
        }
    }
    
    public function create()
    {
        $cart = session()->get('cart', []);
        $shippingZones = ShippingZone::all();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        return view('checkout.create', compact('cart', 'shippingZones'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'payment_method' => 'required|in:credit_card,paypal',
            'notes' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return back()->with('error', 'Your cart is empty.');
            }
            
            // Calculate totals
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $total = 0;
            
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $tax = $subtotal * 0.1; // 10% tax
            $shippingZone = ShippingZone::findOrFail($request->shipping_zone_id);
            $shipping = $shippingZone->calculateShipping($subtotal);
            $total = $subtotal + $tax + $shipping;
            
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'shipping_zone_id' => $request->shipping_zone_id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);
            
            // Create order items and update stock
            foreach ($cart as $id => $item) {
                $product = Product::findOrFail($id);
                
                $order->items()->create([
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
                
                $product->decrement('stock', $item['quantity']);
            }
            
            // Clear cart
            session()->forget('cart');
            
            // Send order confirmation email
            Mail::to($order->user->email)->send(new OrderConfirmation($order));
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error placing your order.');
        }
    }
    
    public function showOrder(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['items.product', 'shippingAddress', 'billingAddress']);
        
        return view('orders.show', compact('order'));
    }
    
    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'This order cannot be cancelled.');
        }
        
        try {
            DB::beginTransaction();
            
            // Update order status
            $order->update([
                'status' => 'cancelled',
                'notes' => 'Order cancelled by customer.'
            ]);
            
            // Restore product stock
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
            }
            
            // Send cancellation email
            Mail::to($order->user->email)->send(new OrderCancelled($order));
            
            DB::commit();
            
            return back()->with('success', 'Order cancelled successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error cancelling the order.');
        }
    }
    
    public function downloadInvoice(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['items.product', 'shippingAddress', 'billingAddress']);
        
        $pdf = PDF::loadView('orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'status' => 'nullable|in:pending,processing,shipped,delivered,cancelled',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);
        
        $query = Order::query()
            ->with(['user', 'items.product'])
            ->where(function($q) use ($request) {
                $q->where('id', 'like', "%{$request->query}%")
                    ->orWhereHas('user', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->query}%")
                            ->orWhere('email', 'like', "%{$request->query}%");
                    });
            });
            
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->latest()->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }
} 
} 