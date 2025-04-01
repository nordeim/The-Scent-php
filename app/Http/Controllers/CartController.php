<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'customizations' => $details['customizations'] ?? [],
                    'subtotal' => $product->price * $details['quantity']
                ];
                $total += $product->price * $details['quantity'];
            }
        }
        
        $shippingZones = ShippingZone::all();
        
        return view('cart.index', compact('items', 'total', 'shippingZones'));
    }
    
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'customizations' => 'array',
            'customizations.*' => 'string'
        ]);
        
        $cart = session()->get('cart', []);
        
        // Check stock availability
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.'
            ], 422);
        }
        
        // Calculate price with customizations
        $price = $product->price;
        if ($request->has('customizations')) {
            foreach ($request->customizations as $optionId) {
                $option = $product->customizationOptions()->find($optionId);
                if ($option) {
                    $price += $option->price_adjustment;
                }
            }
        }
        
        // Add or update cart item
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
            if ($request->has('customizations')) {
                $cart[$product->id]['customizations'] = $request->customizations;
            }
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $price,
                'image' => $product->image,
                'customizations' => $request->customizations ?? []
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cart_count' => count($cart)
        ]);
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.customizations' => 'array'
        ]);
        
        $cart = session()->get('cart', []);
        
        foreach ($request->items as $item) {
            $product = Product::find($item['id']);
            
            if (!$product) {
                continue;
            }
            
            // Check stock availability
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock available for {$product->name}."
                ], 422);
            }
            
            // Calculate price with customizations
            $price = $product->price;
            if (isset($item['customizations'])) {
                foreach ($item['customizations'] as $optionId) {
                    $option = $product->customizationOptions()->find($optionId);
                    if ($option) {
                        $price += $option->price_adjustment;
                    }
                }
            }
            
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $price,
                'image' => $product->image,
                'customizations' => $item['customizations'] ?? []
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.'
        ]);
    }
    
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart successfully.',
                'cart_count' => count($cart)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }
    
    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.'
        ]);
    }
    
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'country' => 'required|string|size:2',
            'weight' => 'required|numeric|min:0'
        ]);
        
        $shippingZone = ShippingZone::findForCountry($request->country);
        
        if (!$shippingZone) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping not available for this country.'
            ], 422);
        }
        
        $shippingCost = $shippingZone->calculateShipping($request->weight);
        
        return response()->json([
            'success' => true,
            'shipping_cost' => $shippingCost,
            'estimated_days' => $shippingZone->estimated_days
        ]);
    }
    
    public function getCartSummary()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        $items = [];
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'image' => $product->image
                ];
                $subtotal += $details['price'] * $details['quantity'];
            }
        }
        
        return response()->json([
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'cart_count' => count($cart)
        ]);
    }
} 