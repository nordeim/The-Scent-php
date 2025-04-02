@extends('layouts.app')

@section('title', 'Welcome to ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-8">Welcome to {{ config('app.name') }}</h1>
    
    @if($featuredProducts->isNotEmpty())
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Featured Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="product-card">
                        <h3>{{ $product->name }}</h3>
                        <p class="price">${{ number_format($product->price, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection