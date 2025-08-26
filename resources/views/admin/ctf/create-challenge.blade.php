<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Soal CTF - {{ $ctf->name }} - Cyber Infinity</title>
    
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
    
    <!-- Custom CSS for responsive sidebar -->
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
                left: 0 !important;
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
                    
                    <a href="{{ route('workshops.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
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
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola Challenges
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- CTF Dropdown -->
                    <div x-data="{ open: true }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-trophy w-5 h-5 mr-3"></i>
                                <span>CTF</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                            <a href="{{ route('ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-flag w-4 h-4 mr-3"></i>
                                CTF Events
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola CTF
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
                            <div class="text-xs font-medium space-y-1">
                                <p class="text-blue-600">Lab: {{ auth()->user()->points ?? 0 }} pts</p>
                                <p class="text-purple-600">CTF: {{ auth()->user()->ctf_points ?? 0 }} pts</p>
                            </div>
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
                            <div class="flex items-center mb-2">
                                <a href="{{ route('admin.ctf.challenges', $ctf) }}" class="mr-3 text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <h1 class="text-2xl font-bold text-gray-900">Tambah Soal CTF</h1>
                            </div>
                            <p class="text-sm text-gray-600">Buat soal baru untuk {{ $ctf->name }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.ctf.challenges', $ctf) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Form Content -->
            <main class="flex-1 p-6 bg-gray-50">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6">
                            <form action="{{ route('admin.ctf.challenges.store', $ctf) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Title -->
                                <div class="mb-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Soal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                                           placeholder="Contoh: Web Exploitation 101" required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category, Difficulty, and Points -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div>
                                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <select id="category" name="category" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Web" {{ old('category') == 'Web' ? 'selected' : '' }}>Web</option>
                                            <option value="Crypto" {{ old('category') == 'Crypto' ? 'selected' : '' }}>Crypto</option>
                                            <option value="Forensic" {{ old('category') == 'Forensic' ? 'selected' : '' }}>Forensic</option>
                                            <option value="OSINT" {{ old('category') == 'OSINT' ? 'selected' : '' }}>OSINT</option>
                                            <option value="Reverse" {{ old('category') == 'Reverse' ? 'selected' : '' }}>Reverse Engineering</option>
                                            <option value="Pwn" {{ old('category') == 'Pwn' ? 'selected' : '' }}>Pwn</option>
                                            <option value="Linux" {{ old('category') == 'Linux' ? 'selected' : '' }}>Linux</option>
                                            <option value="Network" {{ old('category') == 'Network' ? 'selected' : '' }}>Network</option>
                                            <option value="Mobile" {{ old('category') == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                            <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                        </select>
                                        @error('category')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tingkat Kesulitan <span class="text-red-500">*</span>
                                        </label>
                                        <select id="difficulty" name="difficulty" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('difficulty') border-red-500 @enderror" required>
                                            <option value="">Pilih Tingkat</option>
                                            <option value="Easy" {{ old('difficulty') == 'Easy' ? 'selected' : '' }}>🟢 Easy</option>
                                            <option value="Medium" {{ old('difficulty') == 'Medium' ? 'selected' : '' }}>🟡 Medium</option>
                                            <option value="Hard" {{ old('difficulty') == 'Hard' ? 'selected' : '' }}>🔴 Hard</option>
                                        </select>
                                        @error('difficulty')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                                            Points <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="points" name="points" value="{{ old('points') }}" min="1" max="1000"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('points') border-red-500 @enderror"
                                               placeholder="100" required>
                                        @error('points')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Nilai points untuk soal ini (1-1000)</p>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi Soal <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="description" name="description" rows="5" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                              placeholder="Jelaskan soal CTF, konteks, dan petunjuk yang diperlukan..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Flag -->
                                <div class="mb-6">
                                    <label for="flag" class="block text-sm font-medium text-gray-700 mb-2">
                                        Flag <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-4 mb-2">
                                        <input type="text" id="flag" name="flag" value="{{ old('flag') }}" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('flag') border-red-500 @enderror"
                                               placeholder="flag{example_flag_here}" required>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="case_sensitive" name="case_sensitive" value="1" {{ old('case_sensitive') ? 'checked' : '' }}
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="case_sensitive" class="ml-2 text-sm text-gray-700">Case Sensitive</label>
                                        </div>
                                    </div>
                                    @error('flag')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Flag yang harus disubmit peserta untuk menyelesaikan soal</p>
                                </div>

                                <!-- Files -->
                                <div class="mb-6" x-data="{ files: [] }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        File Attachment <span class="text-gray-400">(Opsional)</span>
                                    </label>
                                    
                                    <div class="space-y-2 mb-3">
                                        <template x-for="(file, index) in files" :key="index">
                                            <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg">
                                                <input type="file" :name="`files[${index}]`" 
                                                       class="flex-1 text-sm text-gray-500 file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                <button type="button" @click="files.splice(index, 1)" 
                                                        class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <button type="button" @click="files.push('')" 
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200">
                                        <i class="fas fa-plus mr-1"></i>Tambah File
                                    </button>
                                    
                                    <p class="mt-1 text-xs text-gray-500">Upload file yang dibutuhkan untuk menyelesaikan soal (zip, txt, dll)</p>
                                </div>

                                <!-- Hints -->
                                <div class="mb-6" x-data="{ hints: [] }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Hints <span class="text-gray-400">(Opsional)</span>
                                    </label>
                                    
                                    <div class="space-y-3 mb-3">
                                        <template x-for="(hint, index) in hints" :key="index">
                                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="text-sm font-medium text-gray-900">Hint #<span x-text="index + 1"></span></h4>
                                                    <button type="button" @click="hints.splice(index, 1)" 
                                                            class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <input type="text" :name="`hints[${index}][title]`" 
                                                           placeholder="Judul hint..."
                                                           class="px-3 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                                                    <input type="number" :name="`hints[${index}][cost]`" 
                                                           placeholder="Cost (points)" min="1"
                                                           class="px-3 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                                                    <textarea :name="`hints[${index}][content]`" rows="2"
                                                              placeholder="Isi hint..."
                                                              class="px-3 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm"></textarea>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <button type="button" @click="hints.push({title: '', cost: 10, content: ''})" 
                                            class="px-3 py-1 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                                        <i class="fas fa-lightbulb mr-1"></i>Tambah Hint
                                    </button>
                                    
                                    <p class="mt-1 text-xs text-gray-500">Hints yang bisa dibeli peserta dengan CTF points</p>
                                </div>

                                <!-- Status and Max Attempts -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select id="status" name="status" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" required>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Draft = belum selesai, Active = bisa dikerjakan, Hidden = tersembunyi</p>
                                    </div>

                                    <div>
                                        <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                            Maks Percobaan <span class="text-gray-400">(Opsional)</span>
                                        </label>
                                        <input type="number" id="max_attempts" name="max_attempts" value="{{ old('max_attempts') }}" min="1"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_attempts') border-red-500 @enderror"
                                               placeholder="Unlimited">
                                        @error('max_attempts')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk unlimited attempts</p>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                                    <a href="{{ route('admin.ctf.challenges', $ctf) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                        Batal
                                    </a>
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-save mr-2"></i>Buat Soal CTF
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
                                <h3 class="text-sm font-medium text-blue-800">Tips Membuat Soal CTF</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Berikan deskripsi yang jelas dan tidak ambigu</li>
                                        <li>Pastikan flag format konsisten (contoh: flag{...})</li>
                                        <li>Test soal sebelum dipublish ke peserta</li>
                                        <li>Berikan hint yang membantu tapi tidak terlalu mudah</li>
                                        <li>Sesuaikan points dengan tingkat kesulitan soal</li>
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
                title: 'Validation Error!',
                html: '@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                confirmButtonText: 'OK'
            });
        @endif

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
