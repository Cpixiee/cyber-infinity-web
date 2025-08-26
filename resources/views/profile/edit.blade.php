@extends('layouts.master')

@section('title', 'Profile Settings - Cyber Infinity')

@section('header', 'Profile Settings')

@section('content')
<div class="min-h-screen bg-gray-50" style="margin-top: 20px; margin-bottom: 20px;">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-cog mr-3 text-blue-600"></i>
                        Profile Settings
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola informasi akun dan pengaturan keamanan</p>
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

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto">

        <!-- Profile Tabs -->
        <div x-data="{ activeTab: 'profile' }" class="space-y-8">
            <!-- Tab Navigation -->
            <div class="flex flex-wrap justify-center gap-2">
                <button @click="activeTab = 'profile'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'profile', 'bg-white text-gray-700 border-gray-300': activeTab !== 'profile' }" 
                        class="px-4 py-2 border rounded-lg font-medium transition-all duration-200 hover:bg-blue-50">
                    <i class="fas fa-user mr-2"></i>Profile Info
                </button>
                <button @click="activeTab = 'security'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'security', 'bg-white text-gray-700 border-gray-300': activeTab !== 'security' }" 
                        class="px-4 py-2 border rounded-lg font-medium transition-all duration-200 hover:bg-blue-50">
                    <i class="fas fa-shield-alt mr-2"></i>Security
                </button>
                <button @click="activeTab = 'avatar'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'avatar', 'bg-white text-gray-700 border-gray-300': activeTab !== 'avatar' }" 
                        class="px-4 py-2 border rounded-lg font-medium transition-all duration-200 hover:bg-blue-50">
                    <i class="fas fa-camera mr-2"></i>Avatar
                </button>
            </div>

            <!-- Profile Info Tab -->
            <div x-show="activeTab === 'profile'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Informasi Profile</h2>
                </div>

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

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Keamanan Akun</h2>
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
            <div x-show="activeTab === 'avatar'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-camera text-purple-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Foto Profile</h2>
                </div>

                            <div class="text-center">
                                <!-- Current Avatar -->
                                <div class="mb-6">
                                    <img id="currentAvatar" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://via.placeholder.com/120x120/374151/ffffff?text=' . substr(auth()->user()->name, 0, 1) }}" 
                                         alt="Profile Avatar" class="w-32 h-32 rounded-full mx-auto border-4 border-green-400 object-cover">
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
@endsection
