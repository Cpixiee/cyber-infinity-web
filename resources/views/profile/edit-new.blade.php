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
</head>

<body class="bg-gray-900 text-white">
    <div class="min-h-screen flex">
        <!-- Include Sidebar -->
        @include('layouts.sidebar')

        <!-- Main content -->
        <div class="flex-1 lg:ml-0 flex flex-col min-h-screen">
            <!-- Mobile header -->
            <div class="lg:hidden bg-gray-800 border-b border-gray-700 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <button id="mobile-menu-btn" class="text-gray-400 hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-lg font-semibold text-white">Profile Settings</h1>
                <div></div>
            </div>

            <!-- Page header -->
            <header class="bg-gray-800 border-b border-gray-700 flex-shrink-0">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-user-cog mr-3 text-green-400"></i>
                                Profile Settings
                            </h1>
                            <p class="text-gray-400 mt-1">Kelola informasi akun dan pengaturan keamanan</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-400">Poin Anda</p>
                                <p class="text-lg font-bold text-green-400">{{ auth()->user()->points ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-4xl mx-auto">
                    <!-- Profile Tabs -->
                    <div x-data="{ activeTab: 'profile' }" class="space-y-6">
                        <!-- Tab Navigation -->
                        <div class="flex flex-wrap gap-2">
                            <button @click="activeTab = 'profile'" :class="{ 'bg-green-500 text-black': activeTab === 'profile', 'bg-gray-700 text-gray-300': activeTab !== 'profile' }" 
                                    class="px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-user mr-2"></i>Profile Info
                            </button>
                            <button @click="activeTab = 'security'" :class="{ 'bg-green-500 text-black': activeTab === 'security', 'bg-gray-700 text-gray-300': activeTab !== 'security' }" 
                                    class="px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-shield-alt mr-2"></i>Security
                            </button>
                            <button @click="activeTab = 'avatar'" :class="{ 'bg-green-500 text-black': activeTab === 'avatar', 'bg-gray-700 text-gray-300': activeTab !== 'avatar' }" 
                                    class="px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-camera mr-2"></i>Avatar
                            </button>
                        </div>

                        <!-- Profile Info Tab -->
                        <div x-show="activeTab === 'profile'" x-transition class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-user-edit text-2xl text-green-400 mr-3"></i>
                                <h2 class="text-xl font-bold">Informasi Profile</h2>
                            </div>

                            <form id="profileForm" onsubmit="return handleProfileSubmit(event)">
                                @csrf
                                @method('PATCH')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                            <i class="fas fa-user mr-2 text-green-400"></i>Nama Lengkap
                                        </label>
                                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500 transition-colors duration-200"
                                            placeholder="Masukkan nama lengkap">
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                                            <i class="fas fa-at mr-2 text-blue-400"></i>Username
                                        </label>
                                        <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username ?? '') }}"
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500 transition-colors duration-200"
                                            placeholder="Pilih username unik">
                                        <p class="text-xs text-gray-400 mt-1">Username akan digunakan untuk login dan profil publik</p>
                                    </div>

                                    <!-- Email -->
                                    <div class="md:col-span-2">
                                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                            <i class="fas fa-envelope mr-2 text-yellow-400"></i>Email Address
                                        </label>
                                        <div class="flex gap-3">
                                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required readonly
                                                class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none"
                                                placeholder="nama@email.com">
                                            <button type="button" onclick="requestEmailChange()" class="px-4 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 font-medium transition-colors duration-200">
                                                <i class="fas fa-edit mr-2"></i>Ubah Email
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Perubahan email memerlukan verifikasi OTP</p>
                                    </div>
                                </div>

                                <div class="mt-8 flex gap-3">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-black px-6 py-3 rounded-lg font-bold transition-colors duration-200" id="profileBtn">
                                        <span id="profileBtnText">
                                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                        </span>
                                        <i id="profileSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                                    </button>
                                    <button type="button" onclick="resetForm()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-undo mr-2"></i>Reset
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="activeTab === 'security'" x-transition class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-shield-alt text-2xl text-red-400 mr-3"></i>
                                <h2 class="text-xl font-bold">Keamanan Akun</h2>
                            </div>

                            <form id="passwordForm" onsubmit="return handlePasswordSubmit(event)">
                                @csrf
                                
                                <div class="space-y-6">
                                    <!-- Current Password -->
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">
                                            <i class="fas fa-lock mr-2 text-red-400"></i>Password Saat Ini
                                        </label>
                                        <input type="password" name="current_password" id="current_password" required
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500 transition-colors duration-200"
                                            placeholder="Masukkan password saat ini">
                                    </div>

                                    <!-- New Password -->
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-300 mb-2">
                                            <i class="fas fa-key mr-2 text-green-400"></i>Password Baru
                                        </label>
                                        <input type="password" name="new_password" id="new_password" required
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500 transition-colors duration-200"
                                            placeholder="Minimal 8 karakter">
                                        <div id="passwordStrength" class="mt-2 text-xs"></div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                            <i class="fas fa-check mr-2 text-blue-400"></i>Konfirmasi Password Baru
                                        </label>
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500 transition-colors duration-200"
                                            placeholder="Masukkan ulang password baru">
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-bold transition-colors duration-200" id="passwordBtn">
                                        <span id="passwordBtnText">
                                            <i class="fas fa-shield-alt mr-2"></i>Update Password
                                        </span>
                                        <i id="passwordSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Avatar Tab -->
                        <div x-show="activeTab === 'avatar'" x-transition class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-camera text-2xl text-purple-400 mr-3"></i>
                                <h2 class="text-xl font-bold">Foto Profile</h2>
                            </div>

                            <div class="text-center">
                                <!-- Current Avatar -->
                                <div class="mb-6">
                                    @if(auth()->user()->avatar)
                                        <img id="currentAvatar" src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                             alt="Profile Avatar" class="w-32 h-32 rounded-full mx-auto border-4 border-green-400 object-cover">
                                    @else
                                        <div id="currentAvatar" class="w-32 h-32 rounded-full mx-auto border-4 border-green-400 bg-gradient-to-r from-green-500 to-blue-500 flex items-center justify-center">
                                            <span class="text-white text-4xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <p class="text-gray-400 text-sm mt-2">Avatar saat ini</p>
                                </div>

                                <!-- Upload Section -->
                                <div class="mb-6">
                                    <input type="file" id="avatarInput" accept="image/*" class="hidden" onchange="handleAvatarUpload(event)">
                                    <button type="button" onclick="document.getElementById('avatarInput').click()" class="bg-green-500 hover:bg-green-600 text-black px-6 py-3 rounded-lg font-bold transition-colors duration-200">
                                        <i class="fas fa-upload mr-2"></i>Pilih Foto Baru
                                    </button>
                                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Maksimal 5MB</p>
                                </div>

                                <!-- Crop Section (Hidden by default) -->
                                <div id="cropSection" class="hidden">
                                    <div class="max-w-md mx-auto mb-4">
                                        <img id="cropImage" style="max-width: 100%;">
                                    </div>
                                    <div class="flex gap-3 justify-center">
                                        <button type="button" onclick="cropAndSave()" class="bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200">
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

    <!-- OTP Modal -->
    <div id="otpModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 border border-gray-700">
            <div class="text-center mb-6">
                <i class="fas fa-envelope-open text-4xl text-green-400 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Verifikasi Email</h3>
                <p class="text-gray-400 text-sm">Masukkan kode OTP yang dikirim ke email baru Anda</p>
            </div>
            
            <form id="otpForm" onsubmit="return handleOtpSubmit(event)">
                <div class="flex justify-center mb-6 gap-2">
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl font-bold bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500" oninput="moveToNext(this, 1)">
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl font-bold bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500" oninput="moveToNext(this, 2)">
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl font-bold bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500" oninput="moveToNext(this, 3)">
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl font-bold bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500" oninput="moveToNext(this, 4)">
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl font-bold bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500" oninput="moveToNext(this, 5)">
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl font-bold bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:border-green-500" oninput="moveToNext(this, 6)">
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-lg font-bold transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>Verifikasi
                    </button>
                    <button type="button" onclick="closeOtpModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <button type="button" onclick="resendOtp()" class="text-green-400 hover:text-green-300 text-sm">
                        <i class="fas fa-redo mr-1"></i>Kirim ulang kode
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/cyber-alerts.js') }}"></script>
    
    <script>
        let croppr;
        let newEmail = '';

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
                
                // Wait for image to load before initializing croppr
                cropImage.onload = function() {
                    croppr = new Croppr(cropImage, {
                        aspectRatio: 1,
                        startSize: [80, 80, '%']
                    });
                };
            };
            reader.readAsDataURL(file);
        }

        // Crop and save avatar
        function cropAndSave() {
            if (!croppr) {
                showErrorToast('Error', 'Crop tool tidak tersedia. Silakan upload ulang gambar.');
                return;
            }
            
            // Check if croppr has the required method
            if (typeof croppr.getCroppedCanvas !== 'function') {
                showErrorToast('Error', 'Crop tool belum siap. Silakan tunggu sebentar dan coba lagi.');
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
                                    const currentAvatar = document.getElementById('currentAvatar');
                                    
                                    // Replace div placeholder with img element if needed
                                    if (currentAvatar.tagName === 'DIV') {
                                        const imgElement = document.createElement('img');
                                        imgElement.id = 'currentAvatar';
                                        imgElement.src = avatarUrl;
                                        imgElement.alt = 'Profile Avatar';
                                        imgElement.className = 'w-32 h-32 rounded-full mx-auto border-4 border-green-400 object-cover';
                                        currentAvatar.parentNode.replaceChild(imgElement, currentAvatar);
                                    } else {
                                        currentAvatar.src = avatarUrl;
                                    }
                                    
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
                        
                    } catch (innerError) {
                        console.error('Inner crop error:', innerError);
                        showErrorToast('Error', 'Gagal memproses gambar. Silakan coba lagi.');
                    }
                }, 100); // Small delay to ensure croppr is ready
                
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

        // Request email change
        function requestEmailChange() {
            const currentEmail = document.getElementById('email').value;
            
            Swal.fire({
                title: 'Ubah Email',
                html: `
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Email Baru</label>
                        <input type="email" id="newEmailInput" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg" placeholder="email@baru.com">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim OTP',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff',
                preConfirm: () => {
                    const newEmailValue = document.getElementById('newEmailInput').value;
                    if (!newEmailValue || !isValidEmail(newEmailValue)) {
                        Swal.showValidationMessage('Email tidak valid');
                        return false;
                    }
                    if (newEmailValue === currentEmail) {
                        Swal.showValidationMessage('Email baru harus berbeda dengan email saat ini');
                        return false;
                    }
                    return newEmailValue;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    newEmail = result.value;
                    sendOtpCode(newEmail);
                }
            });
        }

        // Send OTP code
        function sendOtpCode(email) {
            showLoadingDialog('Mengirim kode OTP...', 'Kode akan dikirim ke email baru Anda');
            
            fetch('{{ route("profile.send-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    showInfoToast('Kode OTP: ' + data.debug_otp, 'Silakan cek email Anda (Debug mode)');
                    document.getElementById('otpModal').classList.remove('hidden');
                } else {
                    showErrorToast('Gagal mengirim OTP', data.message);
                }
            })
            .catch(error => {
                Swal.close();
                showErrorToast('Terjadi kesalahan', 'Silakan coba lagi');
            });
        }

        // OTP input navigation
        function moveToNext(current, nextIndex) {
            if (current.value.length === 1) {
                const inputs = document.querySelectorAll('#otpModal input[type="text"]');
                if (inputs[nextIndex]) {
                    inputs[nextIndex].focus();
                }
            }
        }

        // Handle OTP submit
        function handleOtpSubmit(event) {
            event.preventDefault();
            
            const otpInputs = document.querySelectorAll('#otpModal input[type="text"]');
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            
            if (otp.length !== 6) {
                showErrorToast('Kode OTP tidak lengkap', 'Silakan masukkan 6 digit kode');
                return false;
            }
            
            showLoadingDialog('Memverifikasi kode...', 'Mohon tunggu sebentar');
            
            fetch('{{ route("profile.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    email: newEmail,
                    otp: otp 
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    document.getElementById('email').value = newEmail;
                    showSuccessToast('Email berhasil diperbarui!');
                    closeOtpModal();
                } else {
                    showErrorToast('Kode OTP tidak valid', data.message);
                }
            })
            .catch(error => {
                Swal.close();
                showErrorToast('Terjadi kesalahan', 'Silakan coba lagi');
            });
            
            return false;
        }

        // Close OTP modal
        function closeOtpModal() {
            document.getElementById('otpModal').classList.add('hidden');
            document.querySelectorAll('#otpModal input[type="text"]').forEach(input => input.value = '');
            newEmail = '';
        }

        // Resend OTP
        function resendOtp() {
            if (!newEmail) return;
            sendOtpCode(newEmail);
        }

        // Reset form
        function resetForm() {
            document.getElementById('profileForm').reset();
            showInfoToast('Form telah direset');
        }

        // Show flash messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showSuccessToast("{{ session('success') }}");
            @endif

            @if(session('error'))
                showErrorToast("{{ session('error') }}");
            @endif

            @if($errors->any())
                showValidationErrors(@json($errors->all()));
            @endif
        });
    </script>
</body>
</html>
