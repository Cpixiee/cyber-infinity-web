<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Challenges - Cyber Infinity</title>
    
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
<body class="bg-gray-50">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="block lg:!hidden fixed top-4 left-4 z-[60] p-2 bg-white rounded-lg shadow-md border border-gray-200">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="block lg:!hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <div class="flex min-h-screen bg-gray-50">
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
                    <div x-data="{ open: true }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-flag w-5 h-5 mr-3"></i>
                                <span>Challenges</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                            <a href="{{ route('challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg">
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
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
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
                            <a href="{{ route('admin.ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Manage CTF
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                        Registrasi Workshop
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
                            <h1 class="text-2xl font-bold text-gray-900">Challenge Rooms</h1>
                            <p class="text-sm text-gray-600">Uji kemampuan cyber security Anda dengan berbagai challenge</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                                <span>{{ auth()->user()->points ?? 0 }} poin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Filters -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <form method="GET" class="flex flex-wrap items-center gap-4">
                    <!-- Search -->
                    <div class="flex-1 min-w-64">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 placeholder-gray-400"
                                   placeholder="Cari challenge...">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select name="category" class="px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Difficulty Filter -->
                    <div>
                        <select name="difficulty" class="px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Semua Kesulitan</option>
                            @foreach($difficulties as $difficulty)
                                <option value="{{ $difficulty }}" {{ request('difficulty') == $difficulty ? 'selected' : '' }}>
                                    {{ $difficulty }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                                        <!-- Submit Button -->
                    <button type="submit" class="px-4 py-2.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>

                    <!-- Reset Button -->
                    @if(request()->hasAny(['search', 'category', 'difficulty']))
                        <a href="{{ route('challenges.index') }}" class="px-4 py-2.5 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Challenge Grid -->
            <main class="flex-1 p-6">
                @if($challenges->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($challenges as $challenge)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <!-- Challenge Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $challenge->title }}</h3>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($challenge->description, 100) }}</p>
                                        </div>
                                        <div class="ml-4">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-flag text-blue-600"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Challenge Badges -->
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $challenge->getCategoryColor() }}">
                                            {{ $challenge->category }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $challenge->getDifficultyColor() }}">
                                            {{ $challenge->difficulty }}
                                        </span>
                                    </div>

                                    <!-- Challenge Stats -->
                                    <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-tasks mr-1"></i>
                                            <span>{{ $challenge->tasks->count() }} tasks</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-star mr-1 text-yellow-500"></i>
                                            <span>{{ $challenge->points }} poin</span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar (if user has started) -->
                                    @auth
                                        @php
                                            $userProgress = $challenge->getUserProgress(auth()->user());
                                            $completionPercentage = $challenge->getCompletionPercentage(auth()->user());
                                            $isCompleted = $challenge->isCompletedByUser(auth()->user());
                                        @endphp
                                        
                                        @if($userProgress->count() > 0)
                                            <div class="mb-4">
                                                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                                    <span>Progress</span>
                                                    <span>{{ $completionPercentage }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                                                </div>
                                                @if($isCompleted)
                                                    <div class="flex items-center text-xs text-green-600 mt-1">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        <span>Completed!</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endauth

                                    <!-- External Link -->
                                    @if($challenge->external_link)
                                        <div class="mb-4">
                                            <a href="{{ $challenge->external_link }}" target="_blank" 
                                               class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                Lab Environment
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('challenges.show', $challenge) }}" 
                                           class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                            @auth
                                                @if($challenge->isCompletedByUser(auth()->user()))
                                                    <i class="fas fa-eye mr-2"></i>Review
                                                @elseif($challenge->getUserProgress(auth()->user())->count() > 0)
                                                    <i class="fas fa-play mr-2"></i>Lanjutkan
                                                @else
                                                    <i class="fas fa-play mr-2"></i>Mulai
                                                @endif
                                            @else
                                                <i class="fas fa-play mr-2"></i>Mulai
                                            @endauth
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($challenges->hasPages())
                        <div class="mt-8">
                            {{ $challenges->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-flag text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada challenge ditemukan</h3>
                        <p class="text-gray-600 mb-4">
                            @if(request()->hasAny(['search', 'category', 'difficulty']))
                                Coba ubah filter pencarian Anda
                            @else
                                Challenge akan muncul di sini setelah admin membuatnya
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'category', 'difficulty']))
                            <a href="{{ route('challenges.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>Reset Filter
                            </a>
                        @endif
                    </div>
                @endif
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
