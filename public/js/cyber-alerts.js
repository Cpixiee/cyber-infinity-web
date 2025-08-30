// Cyber Infinity Alert System
// Global functions untuk digunakan di semua halaman

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show validation errors using SweetAlert2
function showValidationErrors(errors) {
    let errorList;
    if (Array.isArray(errors)) {
        errorList = errors.map(error => `• ${error}`).join('<br>');
    } else if (typeof errors === 'object') {
        errorList = Object.values(errors).flat().map(error => `• ${error}`).join('<br>');
    } else {
        errorList = `• ${errors}`;
    }
    
    Swal.fire({
        title: '<span class="cyber-title">⚠ Validation Error</span>',
        html: `<div class="text-left cyber-text">${errorList}</div>`,
        icon: 'error',
        confirmButtonText: 'Perbaiki',
        background: '#1f2937',
        color: '#fff',
        iconColor: '#ef4444',
        customClass: {
            popup: 'cyber-validation-error',
            title: 'cyber-alert-title',
            htmlContainer: 'cyber-alert-content',
            confirmButton: 'cyber-alert-btn'
        },
        buttonsStyling: false
    });
}

// Show success toast
function showSuccessToast(title, message = '') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        icon: 'success',
        title: title,
        text: message,
        background: '#1f2937',
        color: '#fff',
        iconColor: '#10b981',
        customClass: {
            popup: 'cyber-toast-success'
        }
    });
}

// Show error toast
function showErrorToast(title, message = '') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true,
        icon: 'error',
        title: title,
        text: message,
        background: '#1f2937',
        color: '#fff',
        iconColor: '#ef4444',
        customClass: {
            popup: 'cyber-toast-error'
        }
    });
}

// Show warning toast
function showWarningToast(title, message = '') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        icon: 'warning',
        title: title,
        text: message,
        background: '#1f2937',
        color: '#fff',
        iconColor: '#f59e0b',
        customClass: {
            popup: 'cyber-toast-warning'
        }
    });
}

// Show info toast
function showInfoToast(title, message = '') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        icon: 'info',
        title: title,
        text: message,
        background: '#1f2937',
        color: '#fff',
        iconColor: '#3b82f6',
        customClass: {
            popup: 'cyber-toast-info'
        }
    });
}

// Show confirmation dialog
function showConfirmDialog(title, text, confirmText = 'Ya', cancelText = 'Batal') {
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
        },
        buttonsStyling: false
    });
}

// Show loading dialog
function showLoadingDialog(title = 'Loading...', text = 'Mohon tunggu sebentar') {
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

// Show cyber-themed alert
function showCyberAlert(title, text, type = 'info') {
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
        },
        buttonsStyling: false
    });
}

// Form validation helper
function validateFormFields(formId) {
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

    if (errors.length > 0) {
        showValidationErrors(errors);
        return false;
    }

    return true;
}

// Button loading state helper
function setButtonLoading(buttonId, loading = true, loadingText = 'Loading...') {
    const btn = document.getElementById(buttonId);
    const btnText = btn.querySelector('span') || btn;
    const spinner = btn.querySelector('.fa-spinner');
    
    if (loading) {
        btn.disabled = true;
        btnText.textContent = loadingText;
        if (spinner) spinner.classList.remove('hidden');
    } else {
        btn.disabled = false;
        if (spinner) spinner.classList.add('hidden');
    }
}

// Add field error styling
function addFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('border-red-500', 'field-error');
        field.classList.add('animate-pulse');
        setTimeout(() => {
            field.classList.remove('animate-pulse');
        }, 1000);
    }
}

// Remove field error styling
function removeFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('border-red-500', 'field-error');
    }
}

// Password strength checker
function getPasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

// Show password strength indicator
function showPasswordStrength(strength, targetId = null) {
    const strengths = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#10b981'];
    
    if (targetId) {
        const target = document.getElementById(targetId);
        if (target) {
            target.textContent = strengths[strength] || 'Tidak Valid';
            target.style.color = colors[strength] || '#6b7280';
        }
    }
    
    return {
        text: strengths[strength] || 'Tidak Valid',
        color: colors[strength] || '#6b7280',
        score: strength
    };
}

// Auto-setup event listeners for common form elements
document.addEventListener('DOMContentLoaded', function() {
    // Email field validation
    document.querySelectorAll('input[type="email"]').forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                addFieldError(this.id);
                showWarningToast('Format email tidak valid');
            } else {
                removeFieldError(this.id);
            }
        });
    });
    
    // Password field validation
    document.querySelectorAll('input[type="password"]').forEach(field => {
        if (field.name === 'password') {
            field.addEventListener('input', function() {
                const strength = getPasswordStrength(this.value);
                if (this.value.length > 0 && this.value.length < 8) {
                    addFieldError(this.id);
                } else {
                    removeFieldError(this.id);
                }
            });
        }
    });
    
    // Clear errors on focus
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('focus', function() {
            removeFieldError(this.id);
        });
    });
});

// Export functions to global scope
window.isValidEmail = isValidEmail;
window.showValidationErrors = showValidationErrors;
window.showSuccessToast = showSuccessToast;
window.showErrorToast = showErrorToast;
window.showWarningToast = showWarningToast;
window.showInfoToast = showInfoToast;
window.showConfirmDialog = showConfirmDialog;
window.showLoadingDialog = showLoadingDialog;
window.showCyberAlert = showCyberAlert;
window.validateFormFields = validateFormFields;
window.setButtonLoading = setButtonLoading;
window.addFieldError = addFieldError;
window.removeFieldError = removeFieldError;
window.getPasswordStrength = getPasswordStrength;
window.showPasswordStrength = showPasswordStrength;


















