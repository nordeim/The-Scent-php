export default function errorBoundary() {
    return {
        error: null,
        
        handleError(error) {
            this.error = error;
            console.error('Error caught by boundary:', error);
        },
        
        resetError() {
            this.error = null;
        },
        
        template: `
            <div x-data="errorBoundary()"
                 @error.window="handleError($event.detail)">
                <template x-if="!error">
                    <slot></slot>
                </template>
                <template x-if="error">
                    <div role="alert" class="bg-red-50 p-4 rounded-lg">
                        <h3 class="text-red-800 font-medium">Something went wrong</h3>
                        <p class="text-red-600 mt-2" x-text="error?.message"></p>
                        <button @click="resetError" 
                                class="mt-4 text-red-700 underline">
                            Try Again
                        </button>
                    </div>
                </template>
            </div>
        `
    };
}
