<section id="testimonials" class="py-24 bg-sage-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-sage-600 font-medium tracking-wider">TESTIMONIALS</span>
            <h2 class="text-4xl font-serif mt-4">What Our Customers Say</h2>
            <p class="text-sage-700 mt-6 max-w-2xl mx-auto">
                Discover how our aromatherapy products have enhanced the lives of our valued customers.
            </p>
        </div>

        <div class="relative" x-data="{ 
            currentSlide: 0,
            testimonials: [
                {
                    name: 'Sarah Johnson',
                    role: 'Yoga Instructor',
                    image: '{{ asset('images/avatars/sarah.jpg') }}',
                    content: 'The essential oils from The Scent have transformed my meditation practice. The quality and purity are unmatched, and the scents are perfectly balanced for relaxation.'
                },
                {
                    name: 'Michael Chen',
                    role: 'Wellness Coach',
                    image: '{{ asset('images/avatars/michael.jpg') }}',
                    content: 'As a wellness professional, I\'m very particular about the products I recommend. The Scent\'s natural soaps and oils are now a staple in my practice and personal routine.'
                },
                {
                    name: 'Emma Rodriguez',
                    role: 'Massage Therapist',
                    image: '{{ asset('images/avatars/emma.jpg') }}',
                    content: 'The therapeutic benefits of these essential oils are remarkable. My clients have reported significant improvements in their stress levels and sleep quality.'
                }
            ]
        }">
            <!-- Testimonial Cards -->
            <div class="relative overflow-hidden">
                <div class="flex transition-transform duration-500"
                     :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                    <template x-for="(testimonial, index) in testimonials" :key="index">
                        <div class="w-full flex-shrink-0 px-4">
                            <div class="bg-white rounded-2xl shadow-lg p-8">
                                <div class="flex items-center mb-6">
                                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                                        <img :src="testimonial.image" 
                                             :alt="testimonial.name"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-serif text-lg" x-text="testimonial.name"></h4>
                                        <p class="text-sage-600" x-text="testimonial.role"></p>
                                    </div>
                                </div>
                                <p class="text-sage-700 italic" x-text="testimonial.content"></p>
                                <div class="mt-6 flex justify-center">
                                    <div class="flex space-x-1">
                                        <template x-for="i in 5" :key="i">
                                            <svg class="w-5 h-5" :class="i <= 5 ? 'text-sage-600' : 'text-sage-200'"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-center mt-8 space-x-4">
                <button @click="currentSlide = (currentSlide - 1 + testimonials.length) % testimonials.length"
                        class="w-12 h-12 rounded-full bg-white shadow-md flex items-center justify-center text-sage-600 hover:bg-sage-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button @click="currentSlide = (currentSlide + 1) % testimonials.length"
                        class="w-12 h-12 rounded-full bg-white shadow-md flex items-center justify-center text-sage-600 hover:bg-sage-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Indicators -->
            <div class="flex justify-center mt-6 space-x-2">
                <template x-for="(testimonial, index) in testimonials" :key="index">
                    <button @click="currentSlide = index"
                            class="w-3 h-3 rounded-full"
                            :class="currentSlide === index ? 'bg-sage-600' : 'bg-sage-200'">
                    </button>
                </template>
            </div>
        </div>
    </div>
</section> 