<div x-data="quickView()"
     x-show="isOpen"
     @keydown.escape.window="close"
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog"
     aria-modal="true">
    
    <div class="flex min-h-screen items-center justify-center p-4">
        <div @click="close" class="fixed inset-0 bg-black/30 backdrop-blur-sm"></div>
        
        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl"
             @click.stop>
            <div class="absolute right-4 top-4">
                <button @click="close"
                        class="text-gray-400 hover:text-gray-500"
                        aria-label="Close modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <div class="aspect-w-1 aspect-h-1">
                    <img :src="product?.image" 
                         :alt="product?.name"
                         class="w-full h-full object-cover rounded-lg"
                         loading="lazy">
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-2xl font-serif" x-text="product?.name"></h3>
                    <p class="text-sage-600" x-text="product?.price"></p>
                    <p class="text-gray-600" x-text="product?.description"></p>
                    
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Quantity
                        </label>
                        <div class="flex items-center space-x-4">
                            <button @click="quantity--" 
                                    :disabled="quantity <= 1"
                                    class="p-2 border rounded">−</button>
                            <span x-text="quantity"></span>
                            <button @click="quantity++" 
                                    class="p-2 border rounded">+</button>
                        </div>
                        
                        <button @click="addToCart"
                                class="w-full bg-sage-600 text-white px-6 py-3 rounded-full
                                       hover:bg-sage-700 focus:outline-none focus:ring-2 
                                       focus:ring-sage-500 focus:ring-offset-2"
                                :disabled="isLoading">
                            <span x-show="!isLoading">Add to Cart</span>
                            <span x-show="isLoading">Adding...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
