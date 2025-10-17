/**
 * Contact Form Handler - Madrasah Aliyah Nusantara
 * Handles form validation, submission, and user interactions
 */

class ContactFormHandler {
    constructor() {
        this.form = document.getElementById('contactForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.modal = document.getElementById('successModal');
        this.modalClose = document.getElementById('modalClose');
        
        this.init();
    }

    init() {
        if (!this.form) return;
        
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time validation
        this.form.querySelectorAll('input, textarea').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearError(field));
        });
        
        // Modal controls
        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => this.closeModal());
        }
        
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) this.closeModal();
            });
        }
        
        // Keyboard accessibility
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal && this.modal.classList.contains('show')) {
                this.closeModal();
            }
        });
        
        // Auto-show modal if there's a success message from server
        if (window.contactSuccess) {
            this.showModal();
        }
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';

        // Clear previous error
        this.clearError(field);

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = `${this.getFieldLabel(fieldName)} wajib diisi.`;
        }
        
        // Email validation
        else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid.';
            }
        }
        
        // Message minimum length
        else if (fieldName === 'message' && value && value.length < 10) {
            isValid = false;
            errorMessage = 'Pesan minimal 10 karakter.';
        }
        
        // Message maximum length
        else if (fieldName === 'message' && value && value.length > 1000) {
            isValid = false;
            errorMessage = 'Pesan maksimal 1000 karakter.';
        }
        
        // Name maximum length
        else if (fieldName === 'name' && value && value.length > 255) {
            isValid = false;
            errorMessage = 'Nama maksimal 255 karakter.';
        }
        
        // Subject maximum length
        else if (fieldName === 'subject' && value && value.length > 255) {
            isValid = false;
            errorMessage = 'Subjek maksimal 255 karakter.';
        }

        if (!isValid) {
            this.showError(field, errorMessage);
        }

        return isValid;
    }

    validateForm() {
        const fields = this.form.querySelectorAll('input[required], textarea[required]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    showError(field, message) {
        const errorId = this.getErrorId(field.name);
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
            field.setAttribute('aria-invalid', 'true');
        }
    }

    clearError(field) {
        const errorId = this.getErrorId(field.name);
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.classList.remove('show');
            field.removeAttribute('aria-invalid');
        }
    }

    getErrorId(fieldName) {
        const errorIds = {
            'name': 'nama-error',
            'email': 'email-error',
            'subject': 'subjek-error',
            'message': 'pesan-error'
        };
        return errorIds[fieldName] || `${fieldName}-error`;
    }

    getFieldLabel(fieldName) {
        const labels = {
            'name': 'Nama Lengkap',
            'email': 'Email',
            'subject': 'Subjek',
            'message': 'Pesan'
        };
        return labels[fieldName] || fieldName;
    }

    handleSubmit(e) {
        // Prevent double submission
        if (this.submitBtn && this.submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        
        // Validate form
        if (!this.validateForm()) {
            e.preventDefault();
            // Focus first error field
            const firstError = this.form.querySelector('[aria-invalid="true"]');
            if (firstError) firstError.focus();
            return;
        }

        // Show loading state
        this.setLoadingState(true);
        
        // Form will submit normally to Laravel backend
    }

    setLoadingState(loading) {
        if (!this.submitBtn) return;
        
        this.submitBtn.disabled = loading;
        const btnText = this.submitBtn.querySelector('.btn-text');
        const btnLoading = this.submitBtn.querySelector('.btn-loading');
        
        if (btnText && btnLoading) {
            if (loading) {
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
            } else {
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            }
        }
    }

    showModal() {
        if (!this.modal) return;
        
        this.modal.classList.add('show');
        this.modal.setAttribute('aria-hidden', 'false');
        
        // Focus management
        const closeBtn = this.modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.focus();
            this.trapFocus(this.modal);
        }
    }

    closeModal() {
        if (!this.modal) return;
        
        this.modal.classList.remove('show');
        this.modal.setAttribute('aria-hidden', 'true');
        
        // Return focus to submit button
        if (this.submitBtn) this.submitBtn.focus();
    }

    trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        const handleTabKey = (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            }
        };

        element.addEventListener('keydown', handleTabKey);
        
        // Clean up event listener when modal is closed
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && 
                    mutation.attributeName === 'class' && 
                    !element.classList.contains('show')) {
                    element.removeEventListener('keydown', handleTabKey);
                    observer.disconnect();
                }
            });
        });
        
        observer.observe(element, { attributes: true });
    }

    // Public method to show success modal (can be called from server-side)
    static showSuccessModal() {
        const modal = document.getElementById('successModal');
        if (modal) {
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            
            const closeBtn = modal.querySelector('.modal-close');
            if (closeBtn) closeBtn.focus();
        }
    }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ContactFormHandler();
});

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ContactFormHandler;
}