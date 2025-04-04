<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Scent - Premium Aromatherapy Solutions</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Preload critical assets -->
    <link rel="preload" as="image" href="{{ asset('images/products/featured.jpg') }}">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Critical CSS -->
    <style>
        .critical-hide { opacity: 0 !important; }
        .page-loaded .critical-hide { opacity: 1 !important; }
    </style>
</head>
<body class="font-sans antialiased" 
      x-data="{ mobileMenu: false, pageLoaded: false }" 
      x-init="setTimeout(() => pageLoaded = true, 100)"
      :class="{ 'page-loaded': pageLoaded, 'overflow-hidden': mobileMenu }">
    
    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenu"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-sage-900/50 backdrop-blur-sm lg:hidden"
         @click="mobileMenu = false">
    </div>

    <div x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 transition-all duration-300"
             :class="{ 'bg-white/90 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }">
            @include('partials.navigation')
        </nav>

        <!-- Hero Section -->
        <section class="min-h-screen relative overflow-hidden bg-gradient-to-br from-sage-50 to-sage-100">
            <div class="container mx-auto px-4 h-full flex items-center">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8 text-center lg:text-left">
                        <h1 class="text-5xl lg:text-7xl font-serif font-bold text-sage-900">
                            Discover the <span class="text-sage-600">Essence</span> of Wellbeing
                        </h1>
                        <p class="text-lg lg:text-xl text-sage-700 max-w-2xl">
                            Premium aromatherapy products crafted with the finest natural ingredients from around the world to enhance your mental & physical health.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                            <a href="#products" class="btn-primary">
                                Explore Collection
                            </a>
                            <a href="#scent-finder" class="btn-outline">
                                Find Your Scent
                            </a>
                        </div>
                    </div>
                    
                    <div class="relative hidden lg:block">
                        <!-- Product Showcase with 3D Effect -->
                        <div class="relative w-full h-[600px]"
                             x-data="{ rotate: 0 }"
                             @mousemove="rotate = ($event.clientX - $event.target.offsetLeft) / 25">
                            <div class="absolute inset-0 transform transition-transform duration-300"
                                 :style="`transform: rotateY(${rotate}deg)`">
                                <img src="{{ asset('images/products/featured.jpg') }}" 
                                     alt="Premium Essential Oils Collection"
                                     class="rounded-2xl shadow-2xl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-1/2 h-full bg-sage-200/20 -skew-x-12 transform origin-top"></div>
            @include('partials.floating-elements')
        </section>

        <!-- Rest of the sections -->
        @include('sections.about')
        @include('sections.scent-finder')
        @include('sections.products')
        @include('sections.benefits')
        @include('sections.testimonials')
        @include('sections.newsletter')
        @include('partials.footer')
    </div>

    <!-- Scent Experience Modal -->
    <div x-data="{ open: false }"
         x-show="open"
         @keydown.escape.window="open = false"
         class="fixed inset-0 z-50">
        <!-- Modal content -->
    </div>
</body>
</html>
