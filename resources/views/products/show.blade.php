@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- ...existing header code... -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            <!-- ...existing image code... -->
        </div>

        <!-- Product Info -->
        <div>
            <!-- ...existing basic info code... -->

            <!-- Wellness Benefits -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Wellness Benefits</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-sage-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sage-700">Stress Relief</span>
                            <span class="text-sage-600">{{ $product->wellness_benefits['stress_relief'] }}/10</span>
                        </div>
                        <div class="w-full bg-sage-200 rounded-full h-2">
                            <div class="bg-sage-500 h-2 rounded-full" 
                                 style="width: {{ $product->wellness_benefits['stress_relief'] * 10 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="bg-sage-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sage-700">Natural Score</span>
                            <span class="text-sage-600">{{ $product->wellness_benefits['natural_score'] }}%</span>
                        </div>
                        <div class="w-full bg-sage-200 rounded-full h-2">
                            <div class="bg-sage-500 h-2 rounded-full" 
                                 style="width: {{ $product->wellness_benefits['natural_score'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aromatherapy Guide -->
            <div class="mb-8 bg-sage-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Aromatherapy Guide</h3>
                <div class="space-y-4">
                    @foreach($product->scentProfiles as $profile)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-sage-200 flex items-center justify-center mr-3">
                                <i class="{{ $profile->icon_class }} text-sage-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-sage-700">{{ $profile->name }}</h4>
                                <p class="text-sage-600 text-sm">{{ $profile->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ...existing add to cart form... -->
        </div>
    </div>
</div>
@endsection
