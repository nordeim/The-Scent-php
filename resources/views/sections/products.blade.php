<section id="products" class="py-24 bg-sage-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-sage-600 font-medium tracking-wider">OUR COLLECTION</span>
            <h2 class="text-4xl font-serif mt-4">Signature Aromatherapy Products</h2>
            <p class="text-sage-700 mt-6 max-w-2xl mx-auto">
                Indulge your senses with our curated selection of premium essential oils and natural soaps.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8"
             x-data="productShowcase">
            <!-- Product Cards -->
            <template x-for="(product, index) in products" :key="index">
                <div class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="relative aspect-w-1 aspect-h-1">
                        <img :src="product.image" 
                             :alt="product.name"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 lazy"
                             loading="lazy">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                            <button @click="$dispatch('open-quick-view', { product: product })"
                                    class="btn-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                Quick View
                            </button>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-serif text-sage-800" x-text="product.name"></h3>
                        <p class="text-sage-600 mt-2" x-text="product.price"></p>
                        <button @click="addToCart(product.id)" class="btn-primary mt-4 w-full">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Quick View Modal Placeholder -->
        <div x-data="quickView" @open-quick-view.window="open($event.detail.product)">
            <!-- Modal structure will be injected by the QuickView component -->
        </div>
    </div>
</section>
