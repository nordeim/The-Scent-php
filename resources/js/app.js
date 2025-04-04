import './bootstrap';
import Alpine from 'alpinejs/dist/module.esm';
import intersect from '@alpinejs/intersect/dist/module.esm';
import focus from '@alpinejs/focus/dist/module.esm';
import errorBoundary from './components/ErrorBoundary';
import quickView from './components/QuickView.js'; // Updated path with extension
import { productShowcase, benefitsVisualization } from './animations';

// Performance optimizations
const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            imageObserver.unobserve(img);
        }
    });
});

// Register components
Alpine.data('errorBoundary', errorBoundary);
Alpine.data('quickView', quickView);
Alpine.data('productShowcase', productShowcase);
Alpine.data('benefitsVisualization', benefitsVisualization);

// Add plugins
Alpine.plugin(intersect);
Alpine.plugin(focus);

// Global error handler
window.addEventListener('error', (event) => {
    Alpine.dispatch(document, 'error', { 
        detail: {
            message: event.message,
            source: event.filename,
            lineNo: event.lineno
        }
    });
});

// Initialize
window.Alpine = Alpine;
Alpine.start();

// Initialize lazy loading
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('img.lazy').forEach(img => {
        imageObserver.observe(img);
    });
});
