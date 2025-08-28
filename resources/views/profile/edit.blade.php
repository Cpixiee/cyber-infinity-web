<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Settings - Cyber Infinity</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Croppr.js for image cropping -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.js"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fix responsive issues -->
    <style>
        /* DESKTOP: Hide all mobile elements and show sidebar */
        @media (min-width: 1024px) {
            #mobile-menu-btn, 
            #mobile-close-btn, 
            #mobile-overlay {
                display: none !important;
            }
            
            #sidebar {
                position: fixed !important;
                transform: translateX(0) !important;
                transition: none !important;
            }
        }
        
        /* MOBILE: Sidebar hidden by default */
        @media (max-width: 1023px) {
            #sidebar {
                transform: translateX(-100%) !important;
            }
            
            #sidebar.mobile-open {
                transform: translateX(0) !important;
            }
        }
    </style>
</head>

<body class="bg-white">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="block lg:!hidden fixed top-4 left-4 z-[60] p-2 bg-white rounded-lg shadow-md border border-gray-200">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="block lg:!hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <div class="flex min-h-screen bg-white">
        <!-- Include Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                            <p class="text-sm text-gray-600">Kelola informasi akun dan pengaturan keamanan</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Poin Lab</p>
                                <p class="text-lg font-bold text-blue-600">{{ auth()->user()->points ?? 0 }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Poin CTF</p>
                                <p class="text-lg font-bold text-purple-600">{{ auth()->user()->ctf_points ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Profile Content -->
            <main class="flex-1 p-6 bg-white">
                <!-- Profile Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-blue-50">
                                <i class="fas fa-user text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Nama Lengkap</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-green-50">
                                <i class="fas fa-at text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Username</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->username ?? 'Belum diset' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-purple-50">
                                <i class="fas fa-calendar text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Member Since</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Tabs -->
                <div x-data="{ activeTab: 'profile' }" class="space-y-6">
                    <!-- Tab Navigation -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1">
                        <div class="flex flex-wrap gap-1">
                            <button @click="activeTab = 'profile'" 
                                    :class="{ 'bg-blue-600 text-white': activeTab === 'profile', 'text-gray-600 hover:text-gray-900': activeTab !== 'profile' }" 
                                    class="flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200">
                                <i class="fas fa-user mr-2"></i>Profile Info
                            </button>
                            <button @click="activeTab = 'security'" 
                                    :class="{ 'bg-blue-600 text-white': activeTab === 'security', 'text-gray-600 hover:text-gray-900': activeTab !== 'security' }" 
                                    class="flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200">
                                <i class="fas fa-shield-alt mr-2"></i>Security
                            </button>
                            <button @click="activeTab = 'avatar'" 
                                    :class="{ 'bg-blue-600 text-white': activeTab === 'avatar', 'text-gray-600 hover:text-gray-900': activeTab !== 'avatar' }" 
                                    class="flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200">
                                <i class="fas fa-camera mr-2"></i>Avatar
                            </button>
                        </div>
                    </div>

                    <!-- Profile Info Tab -->
                    <div x-show="activeTab === 'profile'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-blue-50 mr-4">
                                    <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">Informasi Profile</h2>
                                    <p class="text-sm text-gray-600 mt-1">Update informasi personal dan preferensi akun</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <form id="profileForm" onsubmit="return handleProfileSubmit(event)">
                                @csrf
                                @method('PATCH')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-user mr-2 text-blue-600"></i>Nama Lengkap
                                        </label>
                                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                            placeholder="Masukkan nama lengkap">
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-at mr-2 text-purple-600"></i>Username
                                        </label>
                                        <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username ?? '') }}"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200"
                                            placeholder="Pilih username unik">
                                        <p class="text-xs text-gray-500 mt-1">Username akan digunakan untuk login dan profil publik</p>
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-colors duration-200" id="profileBtn">
                                        <span id="profileBtnText">
                                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                        </span>
                                        <i id="profileSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                                    </button>
                                    <button type="button" onclick="resetForm()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-undo mr-2"></i>Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div x-show="activeTab === 'security'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-red-50 mr-4">
                                    <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">Keamanan Akun</h2>
                                    <p class="text-sm text-gray-600 mt-1">Jaga keamanan akun dengan password yang kuat</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <form id="passwordForm" onsubmit="return handlePasswordSubmit(event)">
                                @csrf
                                
                                <div class="space-y-6">
                                    <!-- Current Password -->
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-2 text-red-600"></i>Password Saat Ini
                                        </label>
                                        <input type="password" name="current_password" id="current_password" required
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200"
                                            placeholder="Masukkan password saat ini">
                                    </div>

                                    <!-- New Password -->
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-key mr-2 text-green-600"></i>Password Baru
                                        </label>
                                        <input type="password" name="new_password" id="new_password" required
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200"
                                            placeholder="Minimal 8 karakter">
                                        <div id="passwordStrength" class="mt-2 text-xs"></div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-check mr-2 text-blue-600"></i>Konfirmasi Password Baru
                                        </label>
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                            placeholder="Masukkan ulang password baru">
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold transition-colors duration-200" id="passwordBtn">
                                        <span id="passwordBtnText">
                                            <i class="fas fa-shield-alt mr-2"></i>Update Password
                                        </span>
                                        <i id="passwordSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Avatar Tab -->
                    <div x-show="activeTab === 'avatar'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-purple-50 mr-4">
                                    <i class="fas fa-camera text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">Foto Profile</h2>
                                    <p class="text-sm text-gray-600 mt-1">Upload dan atur foto profil Anda</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="text-center">
                                <!-- Current Avatar -->
                                <div class="mb-6">
                                    <img id="currentAvatar" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://via.placeholder.com/120x120/374151/ffffff?text=' . substr(auth()->user()->name, 0, 1) }}" 
                                         alt="Profile Avatar" class="w-32 h-32 rounded-full mx-auto border-4 border-blue-100 object-cover">
                                    <p class="text-gray-600 text-sm mt-2">Avatar saat ini</p>
                                </div>

                                <!-- Upload Section -->
                                <div class="mb-6">
                                    <input type="file" id="avatarInput" accept="image/*" class="hidden" onchange="handleAvatarUpload(event)">
                                    <button type="button" onclick="document.getElementById('avatarInput').click()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-colors duration-200">
                                        <i class="fas fa-upload mr-2"></i>Pilih Foto Baru
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 5MB</p>
                                </div>

                                <!-- Crop Section (Hidden by default) -->
                                <div id="cropSection" class="hidden">
                                    <div class="max-w-md mx-auto mb-4 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                        <img id="cropImage" style="max-width: 100%;">
                                    </div>
                                    <div class="flex gap-3 justify-center">
                                        <button type="button" onclick="cropAndSave()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-check mr-2"></i>Crop & Simpan
                                        </button>
                                        <button type="button" onclick="cancelCrop()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-times mr-2"></i>Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let croppr;

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            if (sidebar && overlay) {
                sidebar.classList.add('mobile-open');
                overlay.classList.remove('hidden');
            }
        });

        // Profile form submit
        function handleProfileSubmit(event) {
            event.preventDefault();
            
            setButtonLoading('profileBtn', true, 'Menyimpan...');
            
            if (!validateFormFields('profileForm')) {
                setButtonLoading('profileBtn', false);
                document.getElementById('profileBtnText').innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
                return false;
            }
            
            const formData = new FormData(document.getElementById('profileForm'));
            
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

        // Password form submit
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
            
            fetch('{{ route("profile.password") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

        // Password strength indicator
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strength = getPasswordStrength(password);
            const strengthEl = document.getElementById('passwordStrength');
            
            const result = showPasswordStrength(strength);
            strengthEl.textContent = `Kekuatan password: ${result.text}`;
            strengthEl.style.color = result.color;
        });

        // Avatar upload handler
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
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const cropImage = document.getElementById('cropImage');
                cropImage.src = e.target.result;
                
                document.getElementById('cropSection').classList.remove('hidden');
                
                if (croppr) {
                    croppr.destroy();
                }
                
                croppr = new Croppr(cropImage, {
                    aspectRatio: 1,
                    startSize: [80, 80, '%']
                });
            };
            reader.readAsDataURL(file);
        }

        // Crop and save avatar
        function cropAndSave() {
            if (!croppr) {
                showErrorToast('Error', 'Crop tool tidak tersedia. Silakan upload ulang gambar.');
                return;
            }
            
            try {
                const canvas = croppr.getCroppedCanvas({
                    width: 300,
                    height: 300
                });
                
                if (!canvas) {
                    showErrorToast('Error', 'Gagal memproses gambar. Silakan coba lagi.');
                    return;
                }
                
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
                    
                    fetch('{{ route("profile.avatar") }}', {
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
                            document.getElementById('currentAvatar').src = avatarUrl;
                            showSuccessToast('Avatar berhasil diperbarui!');
                            cancelCrop();
                            
                            // Update sidebar avatar if exists
                            const sidebarAvatar = document.querySelector('.sidebar-avatar, .nav-avatar');
                            if (sidebarAvatar) {
                                sidebarAvatar.src = avatarUrl;
                            }
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
                
            } catch (error) {
                console.error('Crop error:', error);
                showErrorToast('Error', 'Gagal memproses gambar. Silakan coba lagi.');
            }
        }

        // Cancel crop
        function cancelCrop() {
            document.getElementById('cropSection').classList.add('hidden');
            document.getElementById('avatarInput').value = '';
            if (croppr) {
                croppr.destroy();
                croppr = null;
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('profileForm').reset();
            showInfoToast('Form telah direset');
        }

        // Utility functions
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

        // Toast notification functions
        function showSuccessToast(message) {
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

        function showErrorToast(title, message) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonColor: '#ef4444'
            });
        }

        function showInfoToast(message) {
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

        function showLoadingDialog(title, text) {
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

        // Show flash messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showSuccessToast("{{ session('success') }}");
            @endif

            @if(session('error'))
                showErrorToast("Error", "{{ session('error') }}");
            @endif

            @if($errors->any())
                showErrorToast("Validation Error", @json($errors->all()).join('\n'));
            @endif
        });

        // ===== RESPONSIVE SIDEBAR FUNCTIONS =====
        
        function initializeResponsiveSidebar() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileCloseBtn = document.getElementById('mobile-close-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            function isMobile() {
                return window.innerWidth < 1024;
            }
            
            function setupSidebarState() {
                if (isMobile()) {
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
            
            function openMobileMenu() {
                if (!isMobile()) return;
                sidebar?.classList.add('mobile-open');
                overlay?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            
            function closeMobileMenu() {
                if (!isMobile()) return;
                sidebar?.classList.remove('mobile-open');
                overlay?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            mobileMenuBtn?.addEventListener('click', function(e) {
                if (!isMobile()) return;
                e.preventDefault();
                openMobileMenu();
            });
            
            mobileCloseBtn?.addEventListener('click', function(e) {
                if (!isMobile()) return;
                e.preventDefault();
                closeMobileMenu();
            });
            
            overlay?.addEventListener('click', function() {
                if (!isMobile()) return;
                closeMobileMenu();
            });
            
            document.addEventListener('keydown', function(e) {
                if (!isMobile()) return;
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });
            
            window.addEventListener('resize', setupSidebarState);
            setupSidebarState();
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeResponsiveSidebar);
        } else {
            initializeResponsiveSidebar();
        }
    </script>
</body>
</html>