<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Workshop - Cyber Infinity</title>
    
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
                position: static !important;
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
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-sm border-r border-gray-200 fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 lg:!transform-none lg:!translate-x-0 lg:!static lg:!inset-0">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <img src="{{ asset('images/fih-logo.png') }}" alt="FIH Logo" class="w-8 h-8 rounded-lg">
                        <h1 class="ml-3 text-xl font-bold text-gray-900">Cyber Infinity</h1>
                    </div>
                    <!-- Mobile Close Button -->
                    <button id="mobile-close-btn" class="block lg:!hidden p-1 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-home w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('workshops.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
                        <i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
                        Workshop
                    </a>
                    
                    <!-- Challenges Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-flag w-5 h-5 mr-3"></i>
                                <span>Challenges</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                            <a href="{{ route('challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-play w-4 h-4 mr-3"></i>
                                Lihat Challenges
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola Challenges
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        Anggota
                    </a>
                    
                    <a href="{{ route('workshops.create') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-plus-circle w-5 h-5 mr-3"></i>
                        Tambah Workshop
                    </a>
                    @endif
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                        Profile
                    </a>
                </nav>

                <!-- User Profile & Logout -->
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" 
                                 class="w-8 h-8 rounded-full object-cover sidebar-avatar">
                        @else
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600 text-sm"></i>
                            </div>
                        @endif
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ auth()->user()->points ?? 0 }} poin</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Workshop</h1>
                            <p class="text-sm text-gray-600">Edit informasi workshop: {{ $workshop->title }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button onclick="window.location.href='{{ route('workshops.index') }}'" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Form Content -->
            <main class="flex-1 p-6 bg-white">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Workshop</h2>
                            <p class="text-sm text-gray-600 mt-1">Update informasi workshop yang sudah ada</p>
                        </div>
                        
                        <form method="POST" action="{{ route('workshops.update', $workshop) }}" class="p-6 space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Workshop <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" required 
                                           value="{{ old('title', $workshop->title) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                                    @error('title')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Activity Type -->
                                <div>
                                    <label for="activity_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Kegiatan <span class="text-red-500">*</span>
                                    </label>
                                    <select id="activity_type_select" onchange="toggleCustomActivityType()" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('activity_type') border-red-500 @enderror">
                                        <option value="">Pilih Jenis Kegiatan</option>
                                        <option value="workshop" {{ old('activity_type', $workshop->activity_type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                        <option value="bootcamp" {{ old('activity_type', $workshop->activity_type) == 'bootcamp' ? 'selected' : '' }}>Bootcamp</option>
                                        <option value="training" {{ old('activity_type', $workshop->activity_type) == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="custom" {{ old('activity_type', $workshop->activity_type) && !in_array(old('activity_type', $workshop->activity_type), ['workshop', 'bootcamp', 'training']) ? 'selected' : '' }}>🎨 Custom (Ketik Sendiri)</option>
                                    </select>
                                    
                                    <!-- Hidden input for actual form submission -->
                                    <input type="hidden" id="activity_type" name="activity_type" value="{{ old('activity_type', $workshop->activity_type) }}" required>
                                    
                                    <!-- Custom input (hidden by default) -->
                                    <div id="custom_activity_wrapper" class="mt-3" style="display: none;">
                                        <input type="text" id="custom_activity_input" 
                                               placeholder="Masukkan jenis kegiatan custom..."
                                               value="{{ old('activity_type', $workshop->activity_type) && !in_array(old('activity_type', $workshop->activity_type), ['workshop', 'bootcamp', 'training']) ? old('activity_type', $workshop->activity_type) : '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-blue-50"
                                               onkeyup="updateActivityType()">
                                        <p class="text-xs text-gray-500 mt-1">💡 Contoh: Seminar, Pelatihan, Workshop Khusus, dll.</p>
                                    </div>
                                    
                                    @error('activity_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select id="status" name="status" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                        <option value="active" {{ old('status', $workshop->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status', $workshop->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ old('status', $workshop->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $workshop->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="start_date" name="start_date" required 
                                           value="{{ old('start_date', $workshop->start_date->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                                    @error('start_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="end_date" name="end_date" required 
                                           value="{{ old('end_date', $workshop->end_date->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                                    @error('end_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                        Waktu Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" id="start_time" name="start_time" required 
                                           value="{{ old('start_time', $workshop->start_time) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror">
                                    @error('start_time')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                        Durasi (jam) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="duration" name="duration" required step="0.5" min="0.5"
                                           value="{{ old('duration', $workshop->duration) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration') border-red-500 @enderror">
                                    @error('duration')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="location" name="location" required 
                                           value="{{ old('location', $workshop->location) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror">
                                    @error('location')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Target Participants -->
                                <div>
                                    <label for="target_participants" class="block text-sm font-medium text-gray-700 mb-2">
                                        Target Peserta <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="target_participants" name="target_participants" required min="1"
                                           value="{{ old('target_participants', $workshop->target_participants) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('target_participants') border-red-500 @enderror">
                                    @error('target_participants')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="description" name="description" required rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $workshop->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Requirements -->
                                <div class="md:col-span-2">
                                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                        Persyaratan
                                    </label>
                                    <textarea id="requirements" name="requirements" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requirements') border-red-500 @enderror">{{ old('requirements', $workshop->requirements) }}</textarea>
                                    @error('requirements')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <button type="button" onclick="confirmCancel()" 
                                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>Update Workshop
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // ===== LOGOUT CONFIRMATION =====
        
        function confirmLogout() {
            Swal.fire({
                title: 'Logout dari Akun?',
                text: 'Anda akan keluar dari dashboard',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Logging out...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit logout form
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // ===== CANCEL CONFIRMATION =====
        
        function confirmCancel() {
            Swal.fire({
                title: 'Batalkan Perubahan?',
                text: 'Perubahan yang belum disimpan akan hilang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Lanjut Edit'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("workshops.index") }}';
                }
            });
        }

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Show error message if exists
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
                confirmButtonText: 'OK'
            });
        @endif

        // ===== CUSTOM ACTIVITY TYPE =====
        
        function toggleCustomActivityType() {
            const select = document.getElementById('activity_type_select');
            const customWrapper = document.getElementById('custom_activity_wrapper');
            const hiddenInput = document.getElementById('activity_type');
            const customInput = document.getElementById('custom_activity_input');
            
            if (select.value === 'custom') {
                customWrapper.style.display = 'block';
                customInput.focus();
                hiddenInput.value = customInput.value || '';
            } else {
                customWrapper.style.display = 'none';
                hiddenInput.value = select.value;
                customInput.value = '';
            }
        }
        
        function updateActivityType() {
            const customInput = document.getElementById('custom_activity_input');
            const hiddenInput = document.getElementById('activity_type');
            hiddenInput.value = customInput.value;
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we have custom value from database
            const currentActivityType = '{{ old("activity_type", $workshop->activity_type) }}';
            const standardTypes = ['workshop', 'bootcamp', 'training'];
            
            if (currentActivityType && !standardTypes.includes(currentActivityType)) {
                document.getElementById('activity_type_select').value = 'custom';
                toggleCustomActivityType();
            }
        });

        // Form validation enhancement
        document.querySelector('form').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            const activityType = document.getElementById('activity_type').value;
            
            if (endDate < startDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tanggal selesai tidak boleh kurang dari tanggal mulai'
                });
                return false;
            }
            
            if (!activityType || activityType.trim() === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Jenis kegiatan harus diisi'
                });
                return false;
            }
        });
    </script>
</body>
</html>
