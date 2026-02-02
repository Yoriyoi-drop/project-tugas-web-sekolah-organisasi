// Performance optimization utilities
class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.lazyLoadImages();
        this.optimizeScroll();
        this.cacheResources();
        this.minifyDOM();
    }

    // Lazy loading for images
    lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Optimize scroll performance
    optimizeScroll() {
        let ticking = false;
        
        const updateScrollPosition = () => {
            // Update scroll-based animations
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateScrollPosition);
                ticking = true;
            }
        }, { passive: true });
    }

    // Cache frequently used resources
    cacheResources() {
        if ('caches' in window) {
            caches.open('app-cache-v1').then(cache => {
                // Cache critical resources
                const criticalResources = [
                    '/css/app.css',
                    '/js/app.js',
                    '/images/logo.svg'
                ];
                
                criticalResources.forEach(resource => {
                    cache.add(resource).catch(() => {
                        // Ignore cache errors
                    });
                });
            });
        }
    }

    // Minimize DOM operations
    minifyDOM() {
        // Remove unused elements
        const unusedElements = document.querySelectorAll('.temp, .debug-info');
        unusedElements.forEach(el => el.remove());

        // Optimize event listeners
        this.optimizeEventListeners();
    }

    optimizeEventListeners() {
        // Use event delegation for better performance
        document.addEventListener('click', (e) => {
            if (e.target.matches('.btn-delete')) {
                // Handle delete button clicks
                this.handleDeleteClick(e);
            }
        });
    }

    handleDeleteClick(e) {
        // Implementation for delete functionality
        console.log('Delete clicked:', e.target);
    }
}

// Initialize performance optimizer
document.addEventListener('DOMContentLoaded', () => {
    new PerformanceOptimizer();
});

// Service Worker registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
