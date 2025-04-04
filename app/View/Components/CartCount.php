<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\CartService;

class CartCount extends Component
{
    public $cartItems;

    public function __construct(CartService $cartService)
    {
        $this->cartItems = $cartService->getItems();
    }

    public function render()
    {
        return view('components.cart-count');
    }
}
