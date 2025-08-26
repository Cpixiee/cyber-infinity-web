<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buat Challenge - Cyber Infinity</title>
    
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
    <button id="mobile-menu-btn" class="lg:hidden fixed top-4 left-4 z-[60] p-2 bg-white rounded-lg shadow-md border border-gray-200">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <div class="flex min-h-screen bg-white">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-50 shadow-sm border-r border-gray-200 fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 lg:transform-none lg:static lg:inset-0">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <img src="{{ asset('images/fih-logo.png') }}" alt="FIH Logo" class="w-8 h-8 rounded-lg">
                        <h1 class="ml-3 text-xl font-bold text-gray-900">Cyber Infinity</h1>
                    </div>
                    <!-- Mobile Close Button -->
                    <button id="mobile-close-btn" class="lg:hidden p-1 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-home w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('workshops.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
                        Workshop
                    </a>
                    
                    <!-- Challenges Dropdown -->
                    <div x-data="{ open: true }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
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
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola Challenges
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                        Registrasi Workshop
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                        Profile
                    </a>
                </nav>

                <!-- User Profile & Logout -->
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-sm"></i>
                        </div>
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
        <div class="flex-1 lg:ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Buat Challenge Baru</h1>
                            <p class="text-sm text-gray-600">Buat challenge room baru untuk peserta</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.challenges.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Form Content -->
            <main class="flex-1 p-6 bg-gray-50">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6">
                            <form action="{{ route('admin.challenges.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Title -->
                                <div class="mb-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Challenge <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                                           placeholder="Contoh: SQL Injection 101" required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="description" name="description" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                              placeholder="Belajar dasar SQL Injection dengan login bypass..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category and Difficulty -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div x-data="{ 
                                        open: false, 
                                        selected: '{{ old('category') }}', 
                                        custom: '', 
                                        showCustom: false,
                                        categories: ['Web', 'Crypto', 'Forensic', 'OSINT', 'Reverse', 'Pwn', 'Linux', 'Root', 'Network', 'Mobile', 'Hardware']
                                    }" class="relative">
                                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        
                                        <!-- Hidden input for form submission -->
                                        <input type="hidden" name="category" :value="showCustom ? custom : selected" required>
                                        
                                        <!-- Dropdown Button -->
                                        <button type="button" @click="open = !open" 
                                                class="w-full px-3 py-2 text-left border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex items-center justify-between @error('category') border-red-500 @enderror">
                                            <span x-text="showCustom ? custom : (selected || 'Pilih kategori...')" 
                                                  :class="{'text-gray-400': !selected && !showCustom}"></span>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div x-show="open" @click.away="open = false" x-transition
                                             class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                                            
                                            <!-- Predefined Categories -->
                                            <template x-for="category in categories" :key="category">
                                                <button type="button" @click="selected = category; showCustom = false; open = false"
                                                        class="w-full px-3 py-2 text-left hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200"
                                                        :class="{'bg-blue-50 text-blue-700': selected === category && !showCustom}">
                                                    <span x-text="category"></span>
                                                </button>
                                            </template>
                                            
                                            <!-- Custom Category Option -->
                                            <div class="border-t border-gray-200">
                                                <button type="button" @click="showCustom = true; open = false"
                                                        class="w-full px-3 py-2 text-left hover:bg-gray-50 text-gray-600 font-medium">
                                                    <i class="fas fa-plus mr-2"></i>Kategori Custom
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Custom Category Input -->
                                        <div x-show="showCustom" x-transition class="mt-2">
                                            <div class="flex gap-2">
                                                <input type="text" x-model="custom" 
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="Masukkan kategori custom...">
                                                <button type="button" @click="showCustom = false; custom = ''; selected = ''"
                                                        class="px-3 py-2 text-gray-500 hover:text-red-500">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        @error('category')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Pilih dari kategori yang tersedia atau buat kategori custom</p>
                                    </div>

                                    <div>
                                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tingkat Kesulitan <span class="text-red-500">*</span>
                                        </label>
                                        <select id="difficulty" name="difficulty" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('difficulty') border-red-500 @enderror" required>
                                            <option value="">Pilih Kesulitan</option>
                                            @foreach($difficulties as $difficulty)
                                                <option value="{{ $difficulty }}" {{ old('difficulty') == $difficulty ? 'selected' : '' }}>
                                                    {{ $difficulty }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('difficulty')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Points -->
                                <div class="mb-6">
                                    <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                                        Total Poin <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="points" name="points" value="{{ old('points', 100) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('points') border-red-500 @enderror"
                                           placeholder="100" required>
                                    @error('points')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Poin total untuk challenge ini (akan dibagi ke tasks)</p>
                                </div>

                                <!-- External Link -->
                                <div class="mb-6">
                                    <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2">
                                        Link Eksternal <span class="text-gray-400">(Opsional)</span>
                                    </label>
                                    <input type="url" id="external_link" name="external_link" value="{{ old('external_link') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('external_link') border-red-500 @enderror"
                                           placeholder="https://example.com/lab">
                                    @error('external_link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Link ke lab hands-on atau resource eksternal</p>
                                </div>

                                <!-- Status -->
                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select id="status" name="status" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" required>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Draft = belum dipublish, Active = bisa diakses user, Inactive = tidak bisa diakses</p>
                                </div>

                                <!-- Tasks Section -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Tasks/Soal <span class="text-red-500">*</span>
                                        </label>
                                        <button type="button" onclick="addTask()" class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200">
                                            <i class="fas fa-plus mr-1"></i>Tambah Soal
                                        </button>
                                    </div>
                                    
                                    <div id="tasks-container" class="space-y-4">
                                        <!-- Tasks akan ditambahkan di sini secara dinamis -->
                                    </div>
                                    
                                    <p class="mt-2 text-xs text-gray-500">Minimal 1 task diperlukan. Tasks akan dikerjakan secara berurutan.</p>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                                    <a href="{{ route('admin.challenges.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                        Batal
                                    </a>
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-save mr-2"></i>Simpan Challenge
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Langkah Selanjutnya</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Setelah membuat challenge, Anda dapat:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        <li>Menambahkan tasks/soal untuk challenge ini</li>
                                        <li>Mengatur hints berbayar untuk setiap task</li>
                                        <li>Mengaktifkan challenge untuk peserta</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
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
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Show error messages
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Error!',
                html: '@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                confirmButtonText: 'OK'
            });
        @endif

        // Task Management JavaScript
        let taskCounter = 0;

        function addTask() {
            taskCounter++;
            const tasksContainer = document.getElementById('tasks-container');
            
            const taskHtml = `
                <div id="task-${taskCounter}" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-900">Task ${taskCounter}</h4>
                        <button type="button" onclick="removeTask(${taskCounter})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Task Title -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Judul Soal</label>
                            <input type="text" name="tasks[${taskCounter}][title]" 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: Apa nama distro Linux yang sering dipakai?" required>
                        </div>
                        
                        <!-- Task Description -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi/Petunjuk</label>
                            <textarea name="tasks[${taskCounter}][description]" rows="2"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Berikan petunjuk atau penjelasan untuk soal ini..." required></textarea>
                        </div>
                        
                        <!-- Answer and Points -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jawaban (Flag)</label>
                                <input type="text" name="tasks[${taskCounter}][flag]" 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="CYBER{ubuntu}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Poin</label>
                                <input type="number" name="tasks[${taskCounter}][points]" min="0" value="50"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        
                        <!-- External Link -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Link Eksternal (Opsional)</label>
                            <input type="url" name="tasks[${taskCounter}][external_link]" 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="https://example.com/lab">
                            <p class="mt-1 text-xs text-gray-500">Link ke lab hands-on, VM, atau resource eksternal untuk task ini</p>
                        </div>
                        
                        <!-- File Upload -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">File Lampiran (Opsional)</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" name="tasks[${taskCounter}][file]" 
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       accept=".pdf,.doc,.docx,.txt,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.wmv">
                                <span class="text-xs text-gray-500">Max 100MB</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Upload file pendukung seperti gambar, dokumen, atau file lainnya (Max: 100MB)</p>
                        </div>
                        
                        <!-- Hints Section -->
                        <div class="border-t pt-4 mt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Hints (Opsional)
                                </label>
                                <button type="button" onclick="addHint(${taskCounter})" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-lightbulb mr-2 animate-pulse"></i>Tambah Hint
                                </button>
                            </div>
                            <div id="hints-container-${taskCounter}" class="space-y-3">
                                <!-- Hints akan ditambahkan di sini -->
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Hint akan membantu user menyelesaikan challenge dengan mengurangi poin mereka
                            </p>
                        </div>
                    </div>
                    
                    <input type="hidden" name="tasks[${taskCounter}][order]" value="${taskCounter}">
                </div>
            `;
            
            tasksContainer.insertAdjacentHTML('beforeend', taskHtml);
            
            Swal.fire({
                icon: 'success',
                title: 'Task Ditambahkan!',
                text: `Task ${taskCounter} berhasil ditambahkan`,
                showConfirmButton: false,
                timer: 1500
            });
        }

        function removeTask(taskId) {
            Swal.fire({
                title: 'Hapus Task?',
                text: `Apakah Anda yakin ingin menghapus Task ${taskId}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`task-${taskId}`).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Dihapus!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }

        let hintCounters = {};

        function addHint(taskId) {
            if (!hintCounters[taskId]) {
                hintCounters[taskId] = 0;
            }
            hintCounters[taskId]++;
            
            const hintsContainer = document.getElementById(`hints-container-${taskId}`);
            const hintId = `${taskId}-${hintCounters[taskId]}`;
            
            const hintHtml = `
                <div id="hint-${hintId}" class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="text-sm font-medium text-yellow-800">Hint ${hintCounters[taskId]}</h5>
                        <button type="button" onclick="removeHint('${hintId}')" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <!-- Hint Title -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Judul Hint</label>
                            <input type="text" name="tasks[${taskId}][hints][${hintCounters[taskId]}][title]" 
                                   placeholder="Contoh: Cek input sanitization..." 
                                   class="w-full px-2 py-1 text-xs border border-yellow-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500" required>
                        </div>
                        
                        <!-- Content Type -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Konten</label>
                            <select name="tasks[${taskId}][hints][${hintCounters[taskId]}][content_type]" 
                                    class="w-full px-2 py-1 text-xs border border-yellow-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500"
                                    onchange="toggleHintContent(this, '${hintId}')">
                                <option value="text">Text Only</option>
                                <option value="video">Video Only</option>
                                <option value="both">Text + Video</option>
                            </select>
                        </div>
                        
                        <!-- Text Content -->
                        <div id="text-content-${hintId}">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Konten Text</label>
                            <textarea name="tasks[${taskId}][hints][${hintCounters[taskId]}][content]" rows="2"
                                      placeholder="Tuliskan langkah-langkah atau petunjuk untuk menyelesaikan challenge..." 
                                      class="w-full px-2 py-1 text-xs border border-yellow-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500"></textarea>
                        </div>
                        
                        <!-- Video Upload -->
                        <div id="video-content-${hintId}" style="display: none;">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Video Tutorial</label>
                            <input type="file" name="tasks[${taskId}][hints][${hintCounters[taskId]}][video]" 
                                   accept="video/*" 
                                   class="w-full px-2 py-1 text-xs border border-yellow-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-yellow-100 file:text-yellow-700">
                            <p class="text-xs text-gray-500 mt-1">Upload video tutorial (Max: 50MB)</p>
                        </div>
                        
                        <!-- Cost -->
                        <div class="flex items-center gap-2">
                            <label class="block text-xs font-medium text-gray-700">Biaya:</label>
                            <input type="number" name="tasks[${taskId}][hints][${hintCounters[taskId]}][cost]" 
                                   value="10" min="1" max="100"
                                   class="w-20 px-2 py-1 text-xs border border-yellow-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500" required>
                            <span class="text-xs text-yellow-700">poin</span>
                        </div>
                    </div>
                    
                    <input type="hidden" name="tasks[${taskId}][hints][${hintCounters[taskId]}][order]" value="${hintCounters[taskId]}">
                </div>
            `;
            
            hintsContainer.insertAdjacentHTML('beforeend', hintHtml);
        }

        function removeHint(hintId) {
            Swal.fire({
                title: 'Hapus Hint?',
                text: 'Apakah Anda yakin ingin menghapus hint ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`hint-${hintId}`).remove();
                }
            });
        }

        function toggleHintContent(selectElement, hintId) {
            const textContent = document.getElementById(`text-content-${hintId}`);
            const videoContent = document.getElementById(`video-content-${hintId}`);
            const value = selectElement.value;

            // Hide all first
            textContent.style.display = 'none';
            videoContent.style.display = 'none';

            // Show based on selection
            if (value === 'text' || value === 'both') {
                textContent.style.display = 'block';
            }
            if (value === 'video' || value === 'both') {
                videoContent.style.display = 'block';
            }

            // Make content required/optional based on type
            const textArea = textContent.querySelector('textarea');
            const videoInput = videoContent.querySelector('input[type="file"]');
            
            if (value === 'text') {
                textArea.required = true;
                videoInput.required = false;
            } else if (value === 'video') {
                textArea.required = false;
                videoInput.required = true;
            } else if (value === 'both') {
                textArea.required = true;
                videoInput.required = false; // Video is optional when both
            }
        }

        // Add first task automatically
        document.addEventListener('DOMContentLoaded', function() {
            addTask();
        });

        // Responsive sidebar functions
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
