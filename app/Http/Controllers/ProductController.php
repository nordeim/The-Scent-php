<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'moods', 'scentProfiles']);

        // Add filters based on request
        if ($request->has('mood')) {
            $query->whereHas('moods', function($q) use ($request) {
                $q->where('slug', $request->mood);
            });
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['images', 'moods', 'scentProfiles']);
        
        return view('products.show', compact('product'));
    }
}