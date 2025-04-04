export default function quickView() {
    return {
        isOpen: false,
        product: null,
        quantity: 1,
        isLoading: false,

        async open(product) {
            this.product = product;
            this.quantity = 1;
            this.isOpen = true;
            // Trap focus in modal
            this.$nextTick(() => this.$focus.within(this.$el));
        },

        close() {
            this.isOpen = false;
            this.product = null;
            this.quantity = 1;
        },

        async addToCart() {
            if (this.isLoading) return;
            
            this.isLoading = true;
            try {
                await fetch('/api/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        productId: this.product.id,
                        quantity: this.quantity
                    })
                });
                
                this.close();
                // Dispatch event for cart update
                window.dispatchEvent(new CustomEvent('cart-updated'));
            } catch (error) {
                Alpine.dispatch(this.$root, 'error', { 
                    detail: { message: 'Failed to add item to cart' }
                });
            } finally {
                this.isLoading = false;
            }
        }
    };
}
