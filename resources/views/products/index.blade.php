@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-8">Our Products</h1>
    
    @if($products->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="product-card">
                    <h3>{{ $product->name }}</h3>
                    <p class="price">${{ number_format($product->price, 2) }}</p>
                </div>
            @endforeach
        </div>
        
        {{ $products->links() }}
    @else
        <p>No products found.</p>
    @endif
</div>
@endsection