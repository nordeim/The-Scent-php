<section id="benefits" class="py-24 bg-sage-50"
         x-data="benefitsVisualization()"
         x-init="initializeCharts()">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-serif text-center mb-16">Scientifically Proven Benefits</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Benefits Chart -->
            <div class="bg-white rounded-xl p-8 shadow-lg">
                <canvas x-ref="benefitsChart" class="w-full"></canvas>
                <div class="mt-6 space-y-4">
                    <template x-for="stat in statistics" :key="stat.id">
                        <div class="flex items-center space-x-4"
                             x-intersect:enter="animateStat($el, stat)">
                            <div class="w-full bg-sage-100 rounded-full h-2">
                                <div class="bg-sage-600 h-2 rounded-full transition-all duration-1000"
                                     :style="`width: ${stat.value}%`"></div>
                            </div>
                            <span class="text-sage-700 font-medium" x-text="`${stat.value}%`"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Interactive Benefits Grid -->
            <div class="grid grid-cols-2 gap-6">
                <template x-for="benefit in benefits" :key="benefit.id">
                    <div class="bg-white rounded-xl p-6 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-lg"
                         x-intersect:enter="animate($el, benefit)">
                        <div class="text-3xl mb-4" x-text="benefit.icon"></div>
                        <h3 class="text-xl font-serif mb-2" x-text="benefit.title"></h3>
                        <p class="text-sage-600" x-text="benefit.description"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>
