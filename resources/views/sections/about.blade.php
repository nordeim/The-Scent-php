<section id="about" class="py-24 bg-white"
         x-data="{ activeTab: 'mission' }">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-sage-600 font-medium tracking-wider">OUR STORY</span>
            <h2 class="text-4xl font-serif mt-4">The Journey of Scent</h2>
            <p class="text-sage-700 mt-6 max-w-2xl mx-auto">
                Discover the passion and expertise behind our premium aromatherapy products.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Image Section -->
            <div class="relative">
                <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden">
                    <img src="{{ asset('images/backgrounds/about.jpg') }}" 
                         alt="The Scent Workspace"
                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>
                <div class="absolute -bottom-8 -right-8 bg-sage-600 text-white p-8 rounded-2xl shadow-xl">
                    <div class="text-4xl font-serif font-bold">15</div>
                    <div class="text-sm uppercase tracking-wider">Years of Excellence</div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="space-y-8">
                <!-- Tabs -->
                <div class="flex space-x-4 border-b border-sage-200">
                    <button @click="activeTab = 'mission'"
                            :class="{ 'text-sage-600 border-b-2 border-sage-600': activeTab === 'mission' }"
                            class="px-4 py-2 font-medium text-sage-700 hover:text-sage-600 transition-colors">
                        Our Mission
                    </button>
                    <button @click="activeTab = 'values'"
                            :class="{ 'text-sage-600 border-b-2 border-sage-600': activeTab === 'values' }"
                            class="px-4 py-2 font-medium text-sage-700 hover:text-sage-600 transition-colors">
                        Our Values
                    </button>
                    <button @click="activeTab = 'process'"
                            :class="{ 'text-sage-600 border-b-2 border-sage-600': activeTab === 'process' }"
                            class="px-4 py-2 font-medium text-sage-700 hover:text-sage-600 transition-colors">
                        Our Process
                    </button>
                </div>

                <!-- Tab Content -->
                <div x-show="activeTab === 'mission'" class="space-y-6">
                    <h3 class="text-2xl font-serif">Enhancing Lives Through Aromatherapy</h3>
                    <p class="text-sage-700">
                        Our company produces a whole range of aroma therapeutic products where high-quality raw materials from all over the world are imported and our finished products are exported back to these countries.
                    </p>
                    <p class="text-sage-700">
                        This is possible due to our unique and creative product formulations and our knowledge for the various applications, to create harmonious, balanced and well-rounded aromatherapy products.
                    </p>
                </div>

                <div x-show="activeTab === 'values'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-4">
                            <div class="text-sage-600 text-2xl">
                                <i class="fas fa-leaf" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="font-serif text-lg">Natural Ingredients</h4>
                                <p class="text-sage-700">Sourced from the finest global suppliers</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="text-sage-600 text-2xl">
                                <i class="fas fa-flask" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="font-serif text-lg">Expert Formulations</h4>
                                <p class="text-sage-700">Uniquely crafted by our aromatherapy specialists</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="text-sage-600 text-2xl">
                                <i class="fas fa-heart" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="font-serif text-lg">Wellness Focused</h4>
                                <p class="text-sage-700">Promoting both mental and physical health</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="text-sage-600 text-2xl">
                                <i class="fas fa-globe-asia" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="font-serif text-lg">Global Reach</h4>
                                <p class="text-sage-700">Serving customers across the world</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'process'" class="space-y-6">
                    <div class="relative">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-sage-200"></div>
                        <div class="space-y-8 pl-8">
                            <div class="relative">
                                <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-sage-600"></div>
                                <h4 class="font-serif text-lg">Sourcing</h4>
                                <p class="text-sage-700">We carefully select the finest natural ingredients from around the world.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-sage-600"></div>
                                <h4 class="font-serif text-lg">Formulation</h4>
                                <p class="text-sage-700">Our experts create unique blends that promote wellness and balance.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-sage-600"></div>
                                <h4 class="font-serif text-lg">Quality Control</h4>
                                <p class="text-sage-700">Every product undergoes rigorous testing to ensure the highest standards.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-sage-600"></div>
                                <h4 class="font-serif text-lg">Packaging</h4>
                                <p class="text-sage-700">We use sustainable materials to protect our products and the environment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 