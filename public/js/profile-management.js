/**
 * ====================================================
 * PROFILE MANAGEMENT JAVASCRIPT
 * Handles profile forms, avatar upload, and password management
 * ====================================================
 */

// Global variables
let selectedImageFile = null;

/**
 * Initialize profile management when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeProfileManagement();
});

/**
 * Initialize all profile management functionality
 */
function initializeProfileManagement() {
    initializePasswordStrength();
    initializeFlashMessages();
}

/**
 * Handle profile form submission
 */
function handleProfileSubmit(event) {
    event.preventDefault();
    
    setButtonLoading('profileBtn', true, 'Menyimpan...');
    
    if (!validateFormFields('profileForm')) {
        setButtonLoading('profileBtn', false);
        document.getElementById('profileBtnText').innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
        return false;
    }
    
    const formData = new FormData(document.getElementById('profileForm'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(window.profileUpdateRoute || '/profile', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        setButtonLoading('profileBtn', false);
        document.getElementById('profileBtnText').innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
        
        if (data.success) {
            showSuccessToast('Profile berhasil diperbarui!');
        } else {
            showErrorToast('Gagal memperbarui profile', data.message);
        }
    })
    .catch(error => {
        setButtonLoading('profileBtn', false);
        document.getElementById('profileBtnText').innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
        showErrorToast('Terjadi kesalahan', 'Silakan coba lagi');
    });
    
    return false;
}

/**
 * Handle password form submission
 */
function handlePasswordSubmit(event) {
    event.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (newPassword !== confirmPassword) {
        showErrorToast('Password tidak cocok', 'Konfirmasi password harus sama dengan password baru');
        return false;
    }
    
    if (newPassword.length < 8) {
        showErrorToast('Password terlalu pendek', 'Password minimal 8 karakter');
        return false;
    }
    
    setButtonLoading('passwordBtn', true, 'Mengupdate...');
    
    const formData = new FormData(document.getElementById('passwordForm'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(window.passwordUpdateRoute || '/profile/password', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        setButtonLoading('passwordBtn', false);
        document.getElementById('passwordBtnText').innerHTML = '<i class="fas fa-shield-alt mr-2"></i>Update Password';
        
        if (data.success) {
            showSuccessToast('Password berhasil diperbarui!');
            document.getElementById('passwordForm').reset();
        } else {
            showErrorToast('Gagal memperbarui password', data.message);
        }
    })
    .catch(error => {
        setButtonLoading('passwordBtn', false);
        document.getElementById('passwordBtnText').innerHTML = '<i class="fas fa-shield-alt mr-2"></i>Update Password';
        showErrorToast('Terjadi kesalahan', 'Silakan coba lagi');
    });
    
    return false;
}

/**
 * Initialize password strength indicator
 */
function initializePasswordStrength() {
    const passwordInput = document.getElementById('new_password');
    if (!passwordInput) return;
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = getPasswordStrength(password);
        const strengthEl = document.getElementById('passwordStrength');
        
        if (strengthEl) {
            const result = showPasswordStrength(strength);
            strengthEl.textContent = `Kekuatan password: ${result.text}`;
            strengthEl.style.color = result.color;
        }
    });
}

/**
 * Handle avatar upload
 */
function handleAvatarUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('image/')) {
        showErrorToast('File tidak valid', 'Silakan pilih file gambar');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) {
        showErrorToast('File terlalu besar', 'Maksimal ukuran file 5MB');
        return;
    }
    
    selectedImageFile = file;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const canvas = document.getElementById('previewCanvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = function() {
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Calculate crop dimensions to make it square
            const size = Math.min(img.width, img.height);
            const x = (img.width - size) / 2;
            const y = (img.height - size) / 2;
            
            // Draw image as square on canvas
            ctx.drawImage(img, x, y, size, size, 0, 0, canvas.width, canvas.height);
            
            // Show preview section
            document.getElementById('previewSection').classList.remove('hidden');
        };
        
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

/**
 * Upload processed image
 */
function uploadProcessedImage() {
    const canvas = document.getElementById('previewCanvas');
    
    showLoadingDialog('Mengupload avatar...', 'Mohon tunggu sebentar');
    
    canvas.toBlob(function(blob) {
        if (!blob) {
            Swal.close();
            showErrorToast('Error', 'Gagal memproses gambar. Silakan coba lagi.');
            return;
        }
        
        const formData = new FormData();
        formData.append('avatar', blob, 'avatar.jpg');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(window.avatarUpdateRoute || '/profile/avatar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            
            if (data.success) {
                // Update avatar image with cache busting
                const avatarUrl = data.avatar_url + '?t=' + new Date().getTime();
                const currentAvatar = document.getElementById('currentAvatar');
                
                // Replace div placeholder with img element if needed
                if (currentAvatar.tagName === 'DIV') {
                    const imgElement = document.createElement('img');
                    imgElement.id = 'currentAvatar';
                    imgElement.src = avatarUrl;
                    imgElement.alt = 'Profile Avatar';
                    imgElement.className = 'w-32 h-32 rounded-full mx-auto border-4 border-blue-100 object-cover';
                    currentAvatar.parentNode.replaceChild(imgElement, currentAvatar);
                } else {
                    currentAvatar.src = avatarUrl;
                }
                
                showSuccessToast('Avatar berhasil diperbarui!');
                cancelPreview();
                
                // Update all sidebar avatars
                const sidebarAvatars = document.querySelectorAll('.sidebar-avatar');
                sidebarAvatars.forEach(avatar => {
                    avatar.src = avatarUrl;
                });
            } else {
                showErrorToast('Gagal mengupload avatar', data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Upload error:', error);
            showErrorToast('Terjadi kesalahan', 'Gagal mengupload avatar. Silakan coba lagi.');
        });
    }, 'image/jpeg', 0.9);
}

/**
 * Cancel preview
 */
function cancelPreview() {
    document.getElementById('previewSection').classList.add('hidden');
    document.getElementById('avatarInput').value = '';
    selectedImageFile = null;
}

/**
 * Reset form
 */
function resetForm() {
    document.getElementById('profileForm').reset();
    showInfoToast('Form telah direset');
}

/**
 * Utility functions
 */
function setButtonLoading(buttonId, loading, text = '') {
    const button = document.getElementById(buttonId);
    const buttonText = document.getElementById(buttonId + 'Text');
    const spinner = document.getElementById(buttonId + 'Spinner');
    
    if (loading) {
        button.disabled = true;
        if (text) buttonText.textContent = text;
        spinner?.classList.remove('hidden');
    } else {
        button.disabled = false;
        spinner?.classList.add('hidden');
    }
}

function validateFormFields(formId) {
    const form = document.getElementById(formId);
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ef4444';
            isValid = false;
        } else {
            field.style.borderColor = '#d1d5db';
        }
    });
    
    return isValid;
}

function getPasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

function showPasswordStrength(strength) {
    const strengths = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#10b981'];
    
    return {
        text: strengths[strength] || 'Sangat Lemah',
        color: colors[strength] || '#ef4444'
    };
}

/**
 * Toast notification functions
 */
function showSuccessToast(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    }
}

function showErrorToast(title, message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#ef4444'
        });
    }
}

function showInfoToast(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: message,
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    }
}

function showLoadingDialog(title, text) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
}

/**
 * Initialize flash messages
 */
function initializeFlashMessages() {
    // This will be called from blade templates with flash data
}

/**
 * Show flash messages (called from blade templates)
 */
function showFlashMessages(flashData) {
    if (flashData.success) {
        showSuccessToast(flashData.success);
    }
    
    if (flashData.error) {
        showErrorToast("Error", flashData.error);
    }
    
    if (flashData.errors && flashData.errors.length > 0) {
        showErrorToast("Validation Error", flashData.errors.join('\n'));
    }
}
