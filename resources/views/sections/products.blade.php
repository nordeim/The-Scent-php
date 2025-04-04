<section id="products" class="py-24 bg-white"
         x-data="productShowcase()"
         x-init="initializeObserver()">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-serif text-center mb-16">Our Collection</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="product in products" :key="product.id">
                <div class="group perspective"
                     @mousemove="handleHover($event, $el)"
                     @mouseleave="resetCard($el)"
                     x-intersect:enter="animate($el)">
                    <div class="relative transform-gpu transition-transform duration-300 ease-out"
                         :style="getCardStyle($el)">
                        <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                            <img :src="product.image" 
                                 :alt="product.name"
                                 class="w-full h-full object-cover transform transition-transform group-hover:scale-105">
                        </div>
                        <div class="p-6 bg-white/90 backdrop-blur-sm absolute bottom-0 w-full">
                            <h3 class="text-xl font-serif" x-text="product.name"></h3>
                            <p class="text-sage-600 mt-2" x-text="product.price"></p>
                            <button @click="quickView(product)" 
                                    class="mt-4 bg-sage-600 text-white px-6 py-2 rounded-full 
                                           hover:bg-sage-700 transition-colors">
                                Quick View
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>
