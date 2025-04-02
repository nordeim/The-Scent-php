<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        {{ config('app.name') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                        Products
                    </x-nav-link>
                    <x-nav-link :href="route('moods.index')" :active="request()->routeIs('moods.index')">
                        Shop by Mood
                    </x-nav-link>
                    <x-nav-link :href="route('scent-profiles.index')" :active="request()->routeIs('scent-profiles.index')">
                        Scent Profiles
                    </x-nav-link>
                </div>
            </div>

            <!-- Cart -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <a href="{{ route('cart.index') }}" class="flex items-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="ml-2">Cart</span>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                Products
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('moods.index')" :active="request()->routeIs('moods.index')">
                Shop by Mood
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('scent-profiles.index')" :active="request()->routeIs('scent-profiles.index')">
                Scent Profiles
            </x-responsive-nav-link>
        </div>
    </div>
</nav>