/**
 * Bootstrap 5 Responsive Utilities JavaScript
 * Mobile-first responsive enhancements and utilities
 */

class ResponsiveUtils {
    constructor() {
        this.breakpoints = {
            xs: 0,
            sm: 576,
            md: 768,
            lg: 992,
            xl: 1200,
            xxl: 1400
        };
        
        this.init();
    }

    init() {
        this.setupViewportDetection();
        this.setupTouchDetection();
        this.setupOrientationChange();
        this.setupResponsiveImages();
        this.setupResponsiveTables();
        this.setupAccessibility();
        this.setupPerformanceOptimizations();
    }

    /**
     * Viewport and breakpoint detection
     */
    setupViewportDetection() {
        // Add viewport classes to body
        this.updateViewportClasses();
        
        // Update on resize with debouncing
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.updateViewportClasses();
                this.handleResponsiveComponents();
            }, 150);
        });
    }

    updateViewportClasses() {
        const width = window.innerWidth;
        const body = document.body;
        
        // Remove existing viewport classes
        body.classList.remove('viewport-xs', 'viewport-sm', 'viewport-md', 'viewport-lg', 'viewport-xl', 'viewport-xxl');
        
        // Add current viewport class
        if (width >= this.breakpoints.xxl) {
            body.classList.add('viewport-xxl');
        } else if (width >= this.breakpoints.xl) {
            body.classList.add('viewport-xl');
        } else if (width >= this.breakpoints.lg) {
            body.classList.add('viewport-lg');
        } else if (width >= this.breakpoints.md) {
            body.classList.add('viewport-md');
        } else if (width >= this.breakpoints.sm) {
            body.classList.add('viewport-sm');
        } else {
            body.classList.add('viewport-xs');
        }
    }

    getCurrentBreakpoint() {
        const width = window.innerWidth;
        
        if (width >= this.breakpoints.xxl) return 'xxl';
        if (width >= this.breakpoints.xl) return 'xl';
        if (width >= this.breakpoints.lg) return 'lg';
        if (width >= this.breakpoints.md) return 'md';
        if (width >= this.breakpoints.sm) return 'sm';
        return 'xs';
    }

    /**
     * Touch device detection
     */
    setupTouchDetection() {
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        document.body.classList.toggle('touch-device', isTouch);
        document.body.classList.toggle('no-touch', !isTouch);
    }

    /**
     * Orientation change handling
     */
    setupOrientationChange() {
        const handleOrientationChange = () => {
            const isPortrait = window.innerHeight > window.innerWidth;
            document.body.classList.toggle('orientation-portrait', isPortrait);
            document.body.classList.toggle('orientation-landscape', !isPortrait);
            
            // Trigger custom event
            window.dispatchEvent(new CustomEvent('orientationchange', {
                detail: { orientation: isPortrait ? 'portrait' : 'landscape' }
            }));
        };

        handleOrientationChange();
        window.addEventListener('resize', handleOrientationChange);
        window.addEventListener('orientationchange', handleOrientationChange);
    }

    /**
     * Responsive images with lazy loading
     */
    setupResponsiveImages() {
        // Intersection Observer for lazy loading
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Responsive image srcset updates
        this.updateResponsiveImages();
        window.addEventListener('resize', () => this.updateResponsiveImages());
    }

    updateResponsiveImages() {
        const images = document.querySelectorAll('img[data-responsive]');
        const currentBreakpoint = this.getCurrentBreakpoint();
        
        images.forEach(img => {
            const sources = JSON.parse(img.dataset.responsive);
            if (sources[currentBreakpoint]) {
                img.src = sources[currentBreakpoint];
            }
        });
    }

    /**
     * Responsive table enhancements
     */
    setupResponsiveTables() {
        const tables = document.querySelectorAll('.table-responsive-stack');
        
        tables.forEach(table => {
            this.makeTableResponsive(table);
        });
    }

    makeTableResponsive(tableContainer) {
        const table = tableContainer.querySelector('table');
        if (!table) return;

        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
        
        const updateTableDisplay = () => {
            const isMobile = window.innerWidth < this.breakpoints.md;
            
            if (isMobile) {
                table.classList.add('table-stacked');
                this.addDataLabels(table, headers);
            } else {
                table.classList.remove('table-stacked');
                this.removeDataLabels(table);
            }
        };

        updateTableDisplay();
        window.addEventListener('resize', updateTableDisplay);
    }

    addDataLabels(table, headers) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (headers[index]) {
                    cell.setAttribute('data-label', headers[index]);
                }
            });
        });
    }

    removeDataLabels(table) {
        const cells = table.querySelectorAll('tbody td[data-label]');
        cells.forEach(cell => {
            cell.removeAttribute('data-label');
        });
    }

    /**
     * Accessibility enhancements
     */
    setupAccessibility() {
        // Skip link functionality
        this.setupSkipLinks();
        
        // Focus management for modals and offcanvas
        this.setupFocusManagement();
        
        // Keyboard navigation enhancements
        this.setupKeyboardNavigation();
        
        // ARIA live regions for dynamic content
        this.setupLiveRegions();
    }

    setupSkipLinks() {
        const skipLinks = document.querySelectorAll('.skip-link');
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    setupFocusManagement() {
        // Store focus before modal opens
        document.addEventListener('show.bs.modal', (e) => {
            e.target.previousFocus = document.activeElement;
        });

        // Restore focus when modal closes
        document.addEventListener('hidden.bs.modal', (e) => {
            if (e.target.previousFocus) {
                e.target.previousFocus.focus();
            }
        });
    }

    setupKeyboardNavigation() {
        // Escape key to close modals and offcanvas
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                const openOffcanvas = document.querySelector('.offcanvas.show');
                
                if (openModal) {
                    bootstrap.Modal.getInstance(openModal).hide();
                } else if (openOffcanvas) {
                    bootstrap.Offcanvas.getInstance(openOffcanvas).hide();
                }
            }
        });
    }

    setupLiveRegions() {
        // Create live region for announcements
        if (!document.getElementById('live-region')) {
            const liveRegion = document.createElement('div');
            liveRegion.id = 'live-region';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            document.body.appendChild(liveRegion);
        }
    }

    /**
     * Performance optimizations
     */
    setupPerformanceOptimizations() {
        // Reduce animations for users who prefer reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.body.classList.add('reduce-motion');
        }

        // Optimize scroll performance
        this.setupScrollOptimization();
        
        // Preload critical resources
        this.preloadCriticalResources();
    }

    setupScrollOptimization() {
        let ticking = false;
        
        const updateScrollPosition = () => {
            const scrollTop = window.pageYOffset;
            document.body.style.setProperty('--scroll-y', `${scrollTop}px`);
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateScrollPosition);
                ticking = true;
            }
        });
    }

    preloadCriticalResources() {
        // Preload critical images
        const criticalImages = document.querySelectorAll('img[data-preload]');
        criticalImages.forEach(img => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = img.src || img.dataset.src;
            document.head.appendChild(link);
        });
    }

    /**
     * Handle responsive component behavior
     */
    handleResponsiveComponents() {
        const currentBreakpoint = this.getCurrentBreakpoint();
        
        // Update navbar behavior
        this.updateNavbarBehavior(currentBreakpoint);
        
        // Update card layouts
        this.updateCardLayouts(currentBreakpoint);
        
        // Update form layouts
        this.updateFormLayouts(currentBreakpoint);
    }

    updateNavbarBehavior(breakpoint) {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;

        const isMobile = ['xs', 'sm'].includes(breakpoint);
        navbar.classList.toggle('navbar-mobile', isMobile);
    }

    updateCardLayouts(breakpoint) {
        const cardGroups = document.querySelectorAll('.card-group-responsive');
        cardGroups.forEach(group => {
            const isMobile = ['xs', 'sm'].includes(breakpoint);
            group.classList.toggle('card-group-stacked', isMobile);
        });
    }

    updateFormLayouts(breakpoint) {
        const forms = document.querySelectorAll('.form-responsive');
        forms.forEach(form => {
            const isMobile = ['xs', 'sm'].includes(breakpoint);
            form.classList.toggle('form-stacked', isMobile);
        });
    }

    /**
     * Utility methods
     */
    announce(message) {
        const liveRegion = document.getElementById('live-region');
        if (liveRegion) {
            liveRegion.textContent = message;
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }

    isBreakpoint(breakpoint) {
        return this.getCurrentBreakpoint() === breakpoint;
    }

    isMobile() {
        return ['xs', 'sm'].includes(this.getCurrentBreakpoint());
    }

    isTablet() {
        return this.getCurrentBreakpoint() === 'md';
    }

    isDesktop() {
        return ['lg', 'xl', 'xxl'].includes(this.getCurrentBreakpoint());
    }
}

// Initialize responsive utilities when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.responsiveUtils = new ResponsiveUtils();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ResponsiveUtils;
}