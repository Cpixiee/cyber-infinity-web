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
    

    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/responsive-layout.css') }}">
    
    <!-- External JavaScript -->
    <script src="{{ asset('js/mobile-navigation.js') }}"></script>
    <script src="{{ asset('js/profile-management.js') }}"></script>
    
    <script>
        // Set global routes for JavaScript
        window.profileUpdateRoute = "{{ route('profile.update') }}";
        window.passwordUpdateRoute = "{{ route('profile.password') }}";
        window.avatarUpdateRoute = "{{ route('profile.avatar') }}";
    </script>
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
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
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
            <main class="flex-1 p-6 bg-white overflow-y-auto">
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
                                                                    @if(auth()->user()->avatar)
                                    <img id="currentAvatar" src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                         alt="Profile Avatar" class="w-32 h-32 rounded-full mx-auto border-4 border-blue-100 object-cover">
                                @else
                                    <div id="currentAvatar" class="w-32 h-32 rounded-full mx-auto border-4 border-blue-100 bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                        <span class="text-white text-4xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </div>
                                @endif
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

                                                <!-- Preview Section (Hidden by default) -->
                <div id="previewSection" class="hidden">
                    <div class="max-w-md mx-auto mb-4 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <canvas id="previewCanvas" style="max-width: 100%; border: 1px solid #ddd;" width="300" height="300"></canvas>
                        <p class="text-center text-sm text-gray-600 mt-2">Preview gambar yang akan diupload</p>
                    </div>
                    <div class="flex gap-3 justify-center">
                        <button type="button" onclick="uploadProcessedImage()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-upload mr-2"></i>Upload Avatar
                        </button>
                        <button type="button" onclick="cancelPreview()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
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
        // Initialize flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const flashData = {};
            @if(session('success'))
                flashData.success = "{{ session('success') }}";
            @endif
            @if(session('error'))
                flashData.error = "{{ session('error') }}";
            @endif
            @if($errors->any())
                flashData.errors = @json($errors->all());
            @endif
            
            if (Object.keys(flashData).length > 0) {
                showFlashMessages(flashData);
            }
        });




    </script>
</body>
</html>