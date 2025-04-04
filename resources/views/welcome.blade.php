<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'The Scent') }} - Premium Aromatherapy</title>
    <meta name="description" content="Discover the power of scent with The Scent. Experience transformative benefits from premium aromatherapy products crafted with nature's finest ingredients for mental and physical well-being.">
    <meta name="keywords" content="aromatherapy, essential oils, natural soaps, wellness, mental health, physical health, The Scent">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'The Scent') }} - Premium Aromatherapy">
    <meta property="og:description" content="Experience transformative benefits from premium aromatherapy products crafted with nature's finest ingredients.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}"> <!-- Add an Open Graph image -->

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'The Scent') }} - Premium Aromatherapy">
    <meta property="twitter:description" content="Experience transformative benefits from premium aromatherapy products crafted with nature's finest ingredients.">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}"> <!-- Add a Twitter Card image -->

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/apple-touch-icon.png') }}"> <!-- Add apple touch icon -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-white">
        <!-- Mobile Menu -->
        <div x-data="{ open: false }" class="lg:hidden">
            <div x-show="open" class="fixed inset-0 z-40 bg-black bg-opacity-50" @click="open = false"></div>
            <div x-show="open" class="fixed inset-y-0 right-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out" :class="{ 'translate-x-0': open, 'translate-x-full': !open }">
                <div class="p-6">
                    <button @click="open = false" class="absolute top-4 right-4 text-sage-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <nav class="mt-8 space-y-4">
                        <a href="#about" class="block text-sage-700 hover:text-sage-600">About</a>
                        <a href="#products" class="block text-sage-700 hover:text-sage-600">Products</a>
                        <a href="#benefits" class="block text-sage-700 hover:text-sage-600">Benefits</a>
                        <a href="#scent-finder" class="block text-sage-700 hover:text-sage-600">Scent Finder</a>
                        <a href="#testimonials" class="block text-sage-700 hover:text-sage-600">Testimonials</a>
                        <a href="#newsletter" class="block text-sage-700 hover:text-sage-600">Newsletter</a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        @include('partials.navigation')

        <!-- Hero Section -->
        <section class="relative h-screen flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/backgrounds/hero.jpg') }}" 
                     alt="Aromatherapy Products"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            </div>
            <div class="container mx-auto px-4 relative z-10 text-center">
                <h1 class="text-5xl md:text-7xl font-serif text-white mb-6">
                    Discover the Power of Scent
                </h1>
                <p class="text-xl text-sage-100 max-w-2xl mx-auto mb-8">
                    Experience the transformative benefits of premium aromatherapy products crafted with nature's finest ingredients.
                </p>
                <a href="#products" 
                   class="inline-block px-8 py-4 bg-sage-600 text-white rounded-xl font-medium hover:bg-sage-700 transition-colors">
                    Explore Our Collection
                </a>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" x-data x-intersect:enter="$el.classList.add('animate-fade-in')" class="py-24 bg-white opacity-0 transition-opacity duration-1000 ease-out">
            @include('sections.about')
        </section>

        <!-- Products Section -->
        <section id="products" x-data x-intersect:enter="$el.classList.add('animate-fade-in')" class="py-24 bg-sage-50 opacity-0 transition-opacity duration-1000 ease-out">
             @include('sections.products')
        </section>

        <!-- Benefits Section -->
        <section id="benefits" x-data x-intersect:enter="$el.classList.add('animate-fade-in')" class="py-24 bg-white opacity-0 transition-opacity duration-1000 ease-out">
            @include('sections.benefits')
        </section>

        <!-- Scent Finder Section -->
        <section id="scent-finder" x-data x-intersect:enter="$el.classList.add('animate-fade-in')" class="py-24 bg-sage-50 opacity-0 transition-opacity duration-1000 ease-out">
            @include('sections.scent-finder')
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" x-data x-intersect:enter="$el.classList.add('animate-fade-in')" class="py-24 bg-sage-50 opacity-0 transition-opacity duration-1000 ease-out">
            @include('sections.testimonials')
        </section>

        <!-- Newsletter Section -->
        <section id="newsletter" x-data x-intersect:enter="$el.classList.add('animate-fade-in')" class="py-24 bg-sage-600 opacity-0 transition-opacity duration-1000 ease-out">
            @include('sections.newsletter')
        </section>

        <!-- Footer -->
        <footer class="bg-sage-800 text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-serif mb-4">The Scent</h3>
                        <p class="text-sage-300">
                            Enhancing lives through the power of aromatherapy and natural wellness products.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#about" class="text-sage-300 hover:text-white">About Us</a></li>
                            <li><a href="#products" class="text-sage-300 hover:text-white">Products</a></li>
                            <li><a href="#benefits" class="text-sage-300 hover:text-white">Benefits</a></li>
                            <li><a href="#testimonials" class="text-sage-300 hover:text-white">Testimonials</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-4">Contact</h4>
                        <ul class="space-y-2">
                            <li class="text-sage-300">123 Wellness Street</li>
                            <li class="text-sage-300">Aromatherapy City, AC 12345</li>
                            <li class="text-sage-300">info@thescent.com</li>
                            <li class="text-sage-300">+1 (555) 123-4567</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-sage-300 hover:text-white">
                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                <span class="sr-only">Facebook</span>
                            </a>
                            <a href="#" class="text-sage-300 hover:text-white">
                                <i class="fab fa-instagram" aria-hidden="true"></i>
                                <span class="sr-only">Instagram</span>
                            </a>
                            <a href="#" class="text-sage-300 hover:text-white">
                                <i class="fab fa-pinterest" aria-hidden="true"></i>
                                <span class="sr-only">Pinterest</span>
                            </a>
                            <a href="#" class="text-sage-300 hover:text-white">
                                <i class="fab fa-youtube" aria-hidden="true"></i>
                                <span class="sr-only">YouTube</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-sage-700 mt-12 pt-8 text-center text-sage-300">
                    <p>&copy; {{ date('Y') }} The Scent. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
</body>
</html>
