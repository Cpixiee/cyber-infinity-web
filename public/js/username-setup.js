/**
 * ====================================================
 * USERNAME SETUP JAVASCRIPT
 * Handles username setup modal and functionality
 * ====================================================
 */

/**
 * Show username setup modal
 */
function showUsernameSetupModal() {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 not loaded');
        return;
    }
    
    Swal.fire({
        title: 'Setup Username',
        html: `
            <div class="text-left">
                <p class="text-gray-600 mb-4">Silakan pilih username unik untuk akun Anda. Username ini akan digunakan untuk identifikasi di platform.</p>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="usernameInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Masukkan username">
                    <p class="text-xs text-gray-500 mt-1">Hanya huruf, angka, dan underscore yang diperbolehkan</p>
                </div>
            </div>
        `,
        showCancelButton: false,
        confirmButtonText: 'Setup Username',
        allowOutsideClick: false,
        allowEscapeKey: false,
        preConfirm: () => {
            const username = document.getElementById('usernameInput').value.trim();
            
            if (!username) {
                Swal.showValidationMessage('Username tidak boleh kosong');
                return false;
            }
            
            if (username.length < 3) {
                Swal.showValidationMessage('Username minimal 3 karakter');
                return false;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                Swal.showValidationMessage('Username hanya boleh berisi huruf, angka, dan underscore');
                return false;
            }
            
            return username;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            setupUsername(result.value);
        }
    });
}

/**
 * Setup username via API
 */
function setupUsername(username) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Show loading
    Swal.fire({
        title: 'Menyimpan Username...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(window.usernameSetupRoute || '/profile/setup-username', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            username: username
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Username Berhasil Disetup!',
                text: `Username "${username}" telah berhasil disimpan.`,
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then(() => {
                // Reload page to update UI
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Setup Username',
                text: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                // Show modal again
                showUsernameSetupModal();
            });
        }
    })
    .catch(error => {
        console.error('Username setup error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Tidak dapat menghubungi server. Silakan coba lagi.',
            confirmButtonText: 'Coba Lagi'
        }).then(() => {
            // Show modal again
            showUsernameSetupModal();
        });
    });
}
