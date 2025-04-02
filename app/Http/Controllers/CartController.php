<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request, Product $product)
    {
        // TODO: Add cart functionality
        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }

    public function remove(Request $request, $item)
    {
        // TODO: Add remove functionality
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
}