<div class="container mx-auto px-4">
    <div class="flex items-center justify-between py-4">
        <a href="{{ route('home') }}" class="font-serif text-2xl font-bold text-sage-900">
            The Scent
        </a>
        
        <div class="hidden lg:flex items-center space-x-8">
            <a href="#about" class="nav-link">About</a>
            <a href="#products" class="nav-link">Products</a>
            <a href="#scent-finder" class="nav-link">Scent Finder</a>
            <a href="#benefits" class="nav-link">Benefits</a>
            
            <a href="{{ route('cart.index') }}" class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="text-sm font-medium">Cart</span>
            </a>
        </div>

        <!-- Mobile menu button -->
        <button class="lg:hidden" @click="mobileMenu = !mobileMenu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>
