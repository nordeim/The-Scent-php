<section id="newsletter" class="py-24 bg-sage-600">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-sage-200 font-medium tracking-wider">STAY CONNECTED</span>
                <h2 class="text-4xl font-serif mt-4 text-white">Join Our Wellness Journey</h2>
                <p class="text-sage-100 mt-6">
                    Subscribe to our newsletter for exclusive offers, wellness tips, and the latest updates on our premium aromatherapy products.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12" x-data="{ 
                email: '',
                submitted: false,
                error: '',
                async submitForm() {
                    this.error = ''; // Clear previous errors
                    if (!this.email) {
                        this.error = 'Please enter your email address';
                        return;
                    }

                    try {
                        const response = await fetch('{{ route("newsletter.subscribe") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ email: this.email })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (response.status === 422 && data.errors && data.errors.email) {
                                this.error = data.errors.email[0]; // Show specific validation error
                            } else {
                                this.error = data.message || 'An error occurred. Please try again.';
                            }
                            throw new Error(data.message || 'Subscription failed');
                        }

                        this.submitted = true;
                        this.email = ''; // Clear email field on success
                    } catch (err) {
                        console.error('Subscription error:', err);
                        // Error message is already set in the if block above or defaults
                        if (!this.error) {
                            this.error = 'An error occurred during submission. Please try again.';
                        }
                    }
                }
            }">
                <div x-show="!submitted">
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="email" 
                                       x-model="email"
                                       placeholder="Enter your email address"
                                       class="w-full px-6 py-4 rounded-xl border border-sage-200 focus:border-sage-600 focus:ring-2 focus:ring-sage-600 focus:ring-opacity-50 transition-colors">
                                <p x-show="error" x-text="error" class="mt-2 text-red-500"></p>
                            </div>
                            <button type="submit"
                                    class="px-8 py-4 bg-sage-600 text-white rounded-xl font-medium hover:bg-sage-700 transition-colors">
                                Subscribe
                            </button>
                        </div>
                        <p class="text-sage-600 text-sm text-center">
                            By subscribing, you agree to receive marketing communications from us. You can unsubscribe at any time.
                        </p>
                    </form>
                </div>

                <div x-show="submitted" class="text-center">
                    <div class="w-16 h-16 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-serif text-sage-800 mb-4">Thank You for Subscribing!</h3>
                    <p class="text-sage-700">
                        Welcome to our wellness community. We've sent a confirmation email to your inbox.
                    </p>
                </div>
            </div>

            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="text-white">
                    <div class="text-3xl font-serif font-bold mb-2">15K+</div>
                    <div class="text-sage-200">Happy Customers</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-serif font-bold mb-2">50+</div>
                    <div class="text-sage-200">Premium Products</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-serif font-bold mb-2">100%</div>
                    <div class="text-sage-200">Natural Ingredients</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-serif font-bold mb-2">24/7</div>
                    <div class="text-sage-200">Customer Support</div>
                </div>
            </div>
        </div>
    </div>
</section> 