<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'customization_options' => 'nullable|array'
        ]);

        try {
            $this->cartService->add(
                $product,
                $request->input('quantity', 1),
                $request->input('customization_options', [])
            );
            return redirect()->route('cart.index')->with('success', 'Product added to cart');
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to add product to cart');
        }
    }

    public function remove(Request $request, $itemId)
    {
        try {
            $this->cartService->remove($itemId);
            return redirect()->route('cart.index')->with('success', 'Item removed from cart');
        } catch (\Exception $e) {
            Log::error('Remove from cart error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to remove item from cart');
        }
    }
}