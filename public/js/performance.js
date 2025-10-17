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
    
    // Optimize scroll events
    const handleScroll = debounce(() => {
        // Add scroll-based functionality here if needed
    }, 16);
    
    // Optimize resize events
    const handleResize = debounce(() => {
        // Add resize-based functionality here if needed
    }, 250);
    
    // Add event listeners only when needed
    if (document.querySelector('.navbar')) {
        window.addEventListener('scroll', handleScroll, { passive: true });
    }
    
    window.addEventListener('resize', handleResize, { passive: true });
    
    // Lazy load images if any
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
})();