<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = auth()->user()->wishlist()
            ->with(['category', 'reviews'])
            ->latest()
            ->paginate(12);
            
        return view('wishlist.index', compact('wishlist'));
    }
    
    public function store(Request $request, Product $product)
    {
        $user = auth()->user();
        
        if ($user->wishlist()->where('product_id', $product->id)->exists()) {
            return response()->json([
                'message' => 'Product is already in your wishlist.',
                'wishlist_count' => $user->wishlist()->count()
            ]);
        }
        
        $user->wishlist()->attach($product->id);
        
        return response()->json([
            'message' => 'Product added to wishlist successfully!',
            'wishlist_count' => $user->wishlist()->count()
        ]);
    }
    
    public function destroy(Product $product)
    {
        $user = auth()->user();
        
        if (!$user->wishlist()->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Product is not in your wishlist.');
        }
        
        $user->wishlist()->detach($product->id);
        
        return back()->with('success', 'Product removed from wishlist successfully!');
    }
    
    public function moveToCart(Product $product)
    {
        $user = auth()->user();
        
        if (!$user->wishlist()->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Product is not in your wishlist.');
        }
        
        // Remove from wishlist
        $user->wishlist()->detach($product->id);
        
        // Add to cart
        $cart = session()->get('cart', []);
        $cartItemId = $product->id;
        
        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += 1;
        } else {
            $cart[$cartItemId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')
            ->with('success', 'Product moved to cart successfully!');
    }
    
    public function clear()
    {
        $user = auth()->user();
        $user->wishlist()->detach();
        
        return back()->with('success', 'Wishlist cleared successfully!');
    }
} 