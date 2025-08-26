<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CTF Events - Cyber Infinity</title>
    
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
                            <a href="{{ route('ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg">
                                <i class="fas fa-flag w-4 h-4 mr-3"></i>
                                CTF Events
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola CTF
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
                            <h1 class="text-2xl font-bold text-gray-900">CTF Events</h1>
                            <p class="text-sm text-gray-600">Capture The Flag competitions</p>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.ctf.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Buat CTF
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- CTF Content -->
            <main class="flex-1 p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Active CTFs -->
                    @if($activeCtfs->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                                <h2 class="text-xl font-bold text-gray-900">CTF Events Aktif</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($activeCtfs as $ctf)
                                    <div class="bg-white rounded-xl border border-green-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:scale-105">
                                        @if($ctf->banner_image)
                                            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ Storage::url($ctf->banner_image) }}')"></div>
                                        @else
                                            <div class="h-48 bg-gradient-to-r from-green-500 to-blue-500 flex items-center justify-center">
                                                <i class="fas fa-flag text-6xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-bold text-gray-900">{{ $ctf->name }}</h3>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full animate-pulse">
                                                    LIVE
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $ctf->description }}</p>
                                            
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-clock mr-2"></i>
                                                    <span>Berakhir: {{ $ctf->end_time->format('d M Y H:i') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-users mr-2"></i>
                                                    <span>{{ $ctf->getTotalParticipants() }} peserta</span>
                                                </div>
                                                @if($ctf->challenges_count > 0)
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <i class="fas fa-puzzle-piece mr-2"></i>
                                                        <span>{{ $ctf->challenges_count }} soal</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('ctf.show', $ctf) }}" 
                                               class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                <i class="fas fa-play mr-2"></i>Masuk CTF
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upcoming CTFs -->
                    @if($upcomingCtfs->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <h2 class="text-xl font-bold text-gray-900">CTF Events Mendatang</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($upcomingCtfs as $ctf)
                                    <div class="bg-white rounded-xl border border-yellow-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                                        @if($ctf->banner_image)
                                            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ Storage::url($ctf->banner_image) }}')"></div>
                                        @else
                                            <div class="h-48 bg-gradient-to-r from-yellow-500 to-orange-500 flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-6xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-bold text-gray-900">{{ $ctf->name }}</h3>
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                                    UPCOMING
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $ctf->description }}</p>
                                            
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-play-circle mr-2"></i>
                                                    <span>Mulai: {{ $ctf->start_time->format('d M Y H:i') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-stop-circle mr-2"></i>
                                                    <span>Berakhir: {{ $ctf->end_time->format('d M Y H:i') }}</span>
                                                </div>
                                                @if($ctf->max_participants)
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <i class="fas fa-user-friends mr-2"></i>
                                                        <span>Maks: {{ $ctf->max_participants }} peserta</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="bg-gray-100 text-gray-700 font-medium py-3 px-4 rounded-lg text-center">
                                                <i class="fas fa-clock mr-2"></i>
                                                {{ $ctf->getTimeRemaining() }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Ended CTFs -->
                    @if($endedCtfs->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                <h2 class="text-xl font-bold text-gray-900">CTF Events Selesai</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($endedCtfs as $ctf)
                                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 opacity-75">
                                        @if($ctf->banner_image)
                                            <div class="h-48 bg-cover bg-center grayscale" style="background-image: url('{{ Storage::url($ctf->banner_image) }}')"></div>
                                        @else
                                            <div class="h-48 bg-gradient-to-r from-gray-500 to-gray-600 flex items-center justify-center">
                                                <i class="fas fa-flag-checkered text-6xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-bold text-gray-900">{{ $ctf->name }}</h3>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full">
                                                    SELESAI
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $ctf->description }}</p>
                                            
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-calendar-check mr-2"></i>
                                                    <span>Selesai: {{ $ctf->end_time->format('d M Y H:i') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-users mr-2"></i>
                                                    <span>{{ $ctf->getTotalParticipants() }} peserta</span>
                                                </div>
                                            </div>
                                            
                                            <a href="{{ route('ctf.leaderboard', $ctf) }}" 
                                               class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                <i class="fas fa-trophy mr-2"></i>Lihat Hasil
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- No CTFs Available -->
                    @if($activeCtfs->count() == 0 && $upcomingCtfs->count() == 0 && $endedCtfs->count() == 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-flag text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Belum Ada CTF Event</h3>
                            <p class="text-gray-600 mb-8">Tunggu event CTF selanjutnya!</p>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.ctf.create') }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Buat CTF Event
                                </a>
                            @endif
                        </div>
                    @endif
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

        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

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