// Performance optimizations
(function() {
    'use strict';
    
    // Debounce function for event handlers
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Throttle function for high-frequency events
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // Optimize scroll events
    const handleScroll = throttle(() => {
        // Mobile scroll effects
        const scrollBtn = document.querySelector('.mobile-nav-btn');
        if (scrollBtn) {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'flex';
                scrollBtn.style.opacity = '1';
            } else {
                scrollBtn.style.opacity = '0';
                setTimeout(() => {
                    if (window.pageYOffset <= 300) {
                        scrollBtn.style.display = 'none';
                    }
                }, 300);
            }
        }
        
        // Parallax effects for hero sections
        const heroElements = document.querySelectorAll('[data-parallax]');
        heroElements.forEach(element => {
            const speed = element.dataset.parallax || 0.5;
            const yPos = -(window.pageYOffset * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }, 16);
    
    // Optimize resize events
    const handleResize = debounce(() => {
        // Update mobile navigation visibility
        const mobileNav = document.querySelector('.mobile-bottom-nav');
        if (mobileNav) {
            if (window.innerWidth <= 767) {
                mobileNav.style.display = 'flex';
            } else {
                mobileNav.style.display = 'none';
            }
        }
        
        // Adjust body padding for mobile
        const body = document.body;
        if (window.innerWidth <= 767) {
            body.style.paddingBottom = '70px';
        } else {
            body.style.paddingBottom = '0';
        }
    }, 250);
    
    // Lazy load images with Intersection Observer
    function lazyLoadImages() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        // Load the image
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        
                        // Add fade-in effect
                        img.classList.add('fade-in');
                        
                        // Stop observing this image
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            // Observe all images with data-src
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
    
    // Preload critical resources
    function preloadCriticalResources() {
        const criticalResources = [
            '/css/bootstrap.min.css',
            '/css/site.css',
            '/js/bootstrap.bundle.min.js'
        ];
        
        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource;
            link.as = resource.endsWith('.css') ? 'style' : 'script';
            document.head.appendChild(link);
        });
    }
    
    // Optimize form inputs for mobile
    function optimizeMobileForms() {
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            // Prevent zoom on iOS
            if (input.type !== 'file' && input.type !== 'checkbox' && input.type !== 'radio') {
                input.style.fontSize = '16px';
            }
            
            // Add touch-friendly improvements
            input.style.minHeight = '48px';
            input.style.borderRadius = '8px';
        });
    }
    
    // Add smooth scroll behavior
    function addSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Performance monitoring
    function monitorPerformance() {
        // Log Core Web Vitals
        if ('PerformanceObserver' in window) {
            // Largest Contentful Paint
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
            });
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
            
            // First Input Delay
            const fidObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach((entry) => {
                    console.log('FID:', entry.processingStart - entry.startTime);
                });
            });
            fidObserver.observe({ entryTypes: ['first-input'] });
            
            // Cumulative Layout Shift
            const clsObserver = new PerformanceObserver((list) => {
                let clsValue = 0;
                list.getEntries().forEach((entry) => {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                });
                console.log('CLS:', clsValue);
            });
            clsObserver.observe({ entryTypes: ['layout-shift'] });
        }
    }
    
    // Initialize optimizations
    function init() {
        // Add event listeners only when needed
        if (document.querySelector('.navbar') || document.querySelector('.mobile-nav-btn')) {
            window.addEventListener('scroll', handleScroll, { passive: true });
        }
        
        window.addEventListener('resize', handleResize, { passive: true });
        
        // Initialize features
        lazyLoadImages();
        preloadCriticalResources();
        optimizeMobileForms();
        addSmoothScroll();
        monitorPerformance();
        
        // Run resize handler once to set initial state
        handleResize();
        
        // Add loading states for better UX
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.classList.contains('no-loading')) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                    this.disabled = true;
                    
                    // Reset after 3 seconds (adjust as needed)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 3000);
                }
            });
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Expose useful functions globally
    window.performanceOptimizations = {
        debounce,
        throttle,
        lazyLoadImages
    };
})();