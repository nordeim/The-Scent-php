<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CartService
{
    protected $sessionId;

    public function __construct()
    {
        $this->sessionId = Session::get('cart_session_id') ?? $this->generateSessionId();
    }

    protected function generateSessionId()
    {
        $sessionId = Str::random(40);
        Session::put('cart_session_id', $sessionId);
        return $sessionId;
    }

    public function getItems(): Collection
    {
        if (!Schema::hasTable('cart_items')) {
            return collect([]);
        }

        try {
            return CartItem::where('session_id', $this->sessionId)
                ->with('product')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getTotal(): float
    {
        return $this->getItems()->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function add(Product $product, int $quantity = 1, array $customizationOptions = []): void
    {
        $cartItem = CartItem::where('session_id', $this->sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'session_id' => $this->sessionId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'customization_options' => $customizationOptions
            ]);
        }
    }

    public function remove($itemId): void
    {
        CartItem::where('session_id', $this->sessionId)
            ->where('id', $itemId)
            ->delete();
    }
}
