@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-6">Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($cartItems) && $cartItems->count() > 0)
        <div x-data="{ 
            updateQuantity(itemId, quantity) {
                fetch(`/cart/quantity/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ quantity })
                })
                .then(response => response.json())
                .then(data => {
                    window.dispatchEvent(new CustomEvent('cart-updated', { 
                        detail: { count: data.count }
                    }));
                });
            }
        }" class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $item->product->thumbnail_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                    <div class="ml-4">
                                        <h3 class="text-sm font-medium">{{ $item->product->name }}</h3>
                                        @if($item->customization_options)
                                            <div class="text-sm text-gray-500">
                                                <ul>
                                                    @foreach($item->customization_options as $option => $value)
                                                        <li>{{ ucfirst($option) }}: {{ $value }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($item->product->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <button @click="updateQuantity({{ $item->id }}, Math.max(1, $refs.qty{{ $item->id }}.value - 1))"
                                            class="p-1 hover:bg-gray-100 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" x-ref="qty{{ $item->id }}"
                                           value="{{ $item->quantity }}" min="1"
                                           @change="updateQuantity({{ $item->id }}, $event.target.value)"
                                           class="w-16 text-center mx-2 rounded border-gray-300">
                                    <button @click="updateQuantity({{ $item->id }}, parseInt($refs.qty{{ $item->id }}.value) + 1)"
                                            class="p-1 hover:bg-gray-100 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($item->subtotal, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="bg-gray-50 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-lg font-semibold">
                        Total: ${{ number_format($total, 2) }}
                    </div>
                    <div class="space-x-4">
                        <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Clear Cart
                            </button>
                        </form>
                        <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Continue Shopping
                        </a>
                        <a href="{{ route('checkout.index') }}" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">Your cart is empty</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
