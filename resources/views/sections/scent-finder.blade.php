<section id="scent-finder" class="py-20 bg-sage-50"
         x-data="scentFinder()"
         x-cloak>
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-serif text-center mb-12">Find Your Perfect Scent</h2>
        
        <div class="max-w-3xl mx-auto">
            <div x-show="step === 1" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                <h3 class="text-2xl mb-6">How would you like to feel?</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <template x-for="mood in moods" :key="mood.id">
                        <button @click="selectMood(mood)"
                                class="p-6 rounded-lg border-2 transition-all duration-300"
                                :class="selectedMood?.id === mood.id ? 'border-sage-600 bg-sage-50' : 'border-sage-200 hover:border-sage-300'">
                            <span x-text="mood.emoji" class="text-3xl block mb-2"></span>
                            <span x-text="mood.name" class="font-medium"></span>
                        </button>
                    </template>
                </div>
            </div>
            
            <!-- Additional steps here -->
        </div>
    </div>
</section>
