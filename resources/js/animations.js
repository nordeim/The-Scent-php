// Performance optimization utilities
const debounce = (fn, ms) => {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), ms);
    };
};

export const productShowcase = () => ({
    products: [
        // Product data here
    ],
    
    handleHover: debounce((e, el) => {
        if (window.matchMedia('(hover: hover)').matches) {
            requestAnimationFrame(() => {
                const card = el.querySelector('div');
                card.style.willChange = 'transform';
                
                const rect = el.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;
                
                card.style.transform = 
                    `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                
                setTimeout(() => {
                    card.style.willChange = 'auto';
                }, 1000);
            });
        }
    }, 10),
    
    resetCard(el) {
        el.querySelector('div').style.transform = 
            'perspective(1000px) rotateX(0deg) rotateY(0deg)';
    },
    
    animate(el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            el.style.transition = 'all 0.6s ease-out';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 100);
    },
    
    initializeObserver() {
        const options = {
            root: null,
            rootMargin: '20px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    requestAnimationFrame(() => this.animate(entry.target));
                    observer.unobserve(entry.target);
                }
            });
        }, options);
        
        document.querySelectorAll('.product-card').forEach(card => {
            observer.observe(card);
        });
    }
});

export const benefitsVisualization = () => ({
    statistics: [
        { id: 1, label: 'Stress Reduction', value: 85 },
        { id: 2, label: 'Sleep Quality', value: 78 },
        { id: 3, label: 'Mental Clarity', value: 92 },
        { id: 4, label: 'Mood Enhancement', value: 88 }
    ],
    
    benefits: [
        // Benefits data here
    ],
    
    async initializeCharts() {
        try {
            const { Chart } = await import(/* webpackChunkName: "chart" */ 'chart.js/auto');
            const ctx = this.$refs.benefitsChart.getContext('2d');
            
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: this.statistics.map(stat => stat.label),
                    datasets: [{
                        label: 'Aromatherapy Benefits',
                        data: this.statistics.map(stat => stat.value),
                        backgroundColor: 'rgba(117, 147, 120, 0.2)',
                        borderColor: 'rgba(117, 147, 120, 1)',
                        pointBackgroundColor: 'rgba(117, 147, 120, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Failed to load chart:', error);
            // Dispatch error to error boundary
            Alpine.dispatch(this.$root, 'error', {
                detail: { message: 'Failed to load benefits visualization' }
            });
        }
    },
    
    animateStat: debounce((el, stat) => {
        if (!el.isAnimating) {
            el.isAnimating = true;
            requestAnimationFrame(() => this._animateValue(el, stat));
        }
    }, 50),
    
    _animateValue(el, stat) {
        let current = 0;
        const target = stat.value;
        const duration = 1500;
        const increment = target / (duration / 16);
        
        const animate = () => {
            current += increment;
            if (current < target) {
                el.querySelector('div > div').style.width = `${current}%`;
                requestAnimationFrame(animate);
            } else {
                el.querySelector('div > div').style.width = `${target}%`;
            }
        };
        
        requestAnimationFrame(animate);
    }
});
