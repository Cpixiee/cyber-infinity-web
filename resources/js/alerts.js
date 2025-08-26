// Interactive Alert System for Cyber Infinity
class CyberAlert {
    constructor() {
        this.initializeToast();
    }

    // Initialize toast notifications
    initializeToast() {
        // Configure SweetAlert2 defaults
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#1f2937',
            color: '#fff',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        this.Toast = Toast;
    }

    // Success alert
    success(title, message = '') {
        return this.Toast.fire({
            icon: 'success',
            title: title,
            text: message,
            iconColor: '#10b981',
            customClass: {
                popup: 'cyber-toast-success'
            }
        });
    }

    // Error alert
    error(title, message = '') {
        return this.Toast.fire({
            icon: 'error',
            title: title,
            text: message,
            iconColor: '#ef4444',
            timer: 6000,
            customClass: {
                popup: 'cyber-toast-error'
            }
        });
    }

    // Warning alert
    warning(title, message = '') {
        return this.Toast.fire({
            icon: 'warning',
            title: title,
            text: message,
            iconColor: '#f59e0b',
            customClass: {
                popup: 'cyber-toast-warning'
            }
        });
    }

    // Info alert
    info(title, message = '') {
        return this.Toast.fire({
            icon: 'info',
            title: title,
            text: message,
            iconColor: '#3b82f6',
            customClass: {
                popup: 'cyber-toast-info'
            }
        });
    }

    // Confirmation dialog
    confirm(title, text, confirmText = 'Ya', cancelText = 'Batal') {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            background: '#1f2937',
            color: '#fff',
            customClass: {
                popup: 'cyber-modal',
                confirmButton: 'cyber-btn-confirm',
                cancelButton: 'cyber-btn-cancel'
            }
        });
    }

    // Loading alert
    loading(title = 'Loading...', text = 'Mohon tunggu sebentar') {
        return Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            background: '#1f2937',
            color: '#fff',
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Custom cyber-themed alert
    cyber(title, text, type = 'info') {
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ',
            hack: '💀'
        };

        return Swal.fire({
            title: `<span class="cyber-title">${icons[type]} ${title}</span>`,
            html: `<div class="cyber-text">>> ${text}</div>`,
            showConfirmButton: true,
            confirmButtonText: 'OK',
            background: '#0f172a',
            color: '#00ff00',
            customClass: {
                popup: 'cyber-alert',
                title: 'cyber-alert-title',
                htmlContainer: 'cyber-alert-content',
                confirmButton: 'cyber-alert-btn'
            }
        });
    }

    // Form validation error display
    showValidationErrors(errors) {
        let errorList = '';
        if (Array.isArray(errors)) {
            errorList = errors.map(error => `• ${error}`).join('<br>');
        } else if (typeof errors === 'object') {
            errorList = Object.values(errors).flat().map(error => `• ${error}`).join('<br>');
        } else {
            errorList = `• ${errors}`;
        }

        return Swal.fire({
            title: 'Validation Error',
            html: `<div class="text-left">${errorList}</div>`,
            icon: 'error',
            confirmButtonText: 'Perbaiki',
            background: '#1f2937',
            color: '#fff',
            customClass: {
                popup: 'cyber-validation-error'
            }
        });
    }
}

// Initialize global CyberAlert instance
window.cyberAlert = new CyberAlert();

// Form validation helpers
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const requiredFields = form.querySelectorAll('[required]');
    const errors = [];

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            errors.push(`${field.placeholder || field.name || 'Field'} harus diisi`);
            field.classList.add('border-red-500', 'focus:border-red-500');
        } else {
            field.classList.remove('border-red-500', 'focus:border-red-500');
        }
    });

    // Email validation
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (field.value && !isValidEmail(field.value)) {
            errors.push('Format email tidak valid');
            field.classList.add('border-red-500', 'focus:border-red-500');
        }
    });

    // Password confirmation
    const password = form.querySelector('input[name="password"]');
    const passwordConfirm = form.querySelector('input[name="password_confirmation"]');
    if (password && passwordConfirm && password.value !== passwordConfirm.value) {
        errors.push('Konfirmasi password tidak cocok');
        passwordConfirm.classList.add('border-red-500', 'focus:border-red-500');
    }

    if (errors.length > 0) {
        cyberAlert.showValidationErrors(errors);
        return false;
    }

    return true;
};

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Auto-show alerts from session flash messages
document.addEventListener('DOMContentLoaded', function() {
    // Success messages
    if (window.flashSuccess) {
        cyberAlert.success(window.flashSuccess);
    }
    
    // Error messages
    if (window.flashError) {
        cyberAlert.error(window.flashError);
    }
    
    // Warning messages
    if (window.flashWarning) {
        cyberAlert.warning(window.flashWarning);
    }
    
    // Info messages
    if (window.flashInfo) {
        cyberAlert.info(window.flashInfo);
    }

    // Validation errors
    if (window.validationErrors && window.validationErrors.length > 0) {
        cyberAlert.showValidationErrors(window.validationErrors);
    }
});

// Export for use in other files
export default CyberAlert;






