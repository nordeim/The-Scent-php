<nav class="fixed w-full z-50 transition-all duration-300"
     x-data="{ scrolled: false, mobileMenu: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white/90 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2">
                <span class="text-2xl font-serif text-sage-600">The Scent</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="#about" class="text-sage-700 hover:text-sage-600 transition-colors">About</a>
                <a href="#products" class="text-sage-700 hover:text-sage-600 transition-colors">Products</a>
                <a href="#benefits" class="text-sage-700 hover:text-sage-600 transition-colors">Benefits</a>
                <a href="#scent-finder" class="text-sage-700 hover:text-sage-600 transition-colors">Scent Finder</a>
                <a href="#testimonials" class="text-sage-700 hover:text-sage-600 transition-colors">Testimonials</a>
                <a href="#newsletter" class="text-sage-700 hover:text-sage-600 transition-colors">Newsletter</a>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-sage-700">
                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="lg:hidden absolute top-20 left-0 right-0 bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col space-y-4">
                <a href="#about" class="text-sage-700 hover:text-sage-600 transition-colors">About</a>
                <a href="#products" class="text-sage-700 hover:text-sage-600 transition-colors">Products</a>
                <a href="#benefits" class="text-sage-700 hover:text-sage-600 transition-colors">Benefits</a>
                <a href="#scent-finder" class="text-sage-700 hover:text-sage-600 transition-colors">Scent Finder</a>
                <a href="#testimonials" class="text-sage-700 hover:text-sage-600 transition-colors">Testimonials</a>
                <a href="#newsletter" class="text-sage-700 hover:text-sage-600 transition-colors">Newsletter</a>
            </div>
        </div>
    </div>
</nav>
