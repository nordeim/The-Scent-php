<div x-data="{ count: {{ $cartItems->sum('quantity') }} }" 
     class="relative inline-block"
     @cart-updated.window="count = $event.detail.count">
    <a href="{{ route('cart.index') }}" class="text-deep-brown hover:text-sage-green transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <span x-show="count > 0" 
              x-text="count"
              class="absolute -top-2 -right-2 bg-sage-green text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
        </span>
    </a>
</div>
