<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Submissions: {{ $ctf->name }} - Cyber Infinity</title>
    
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
                                <i class="fas fa-play w-4 h-4 mr-3"></i>
                                Lihat CTF
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola CTF
                            </a>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-flag w-5 h-5 mr-3"></i>
                        Challenges
                    </a>
                    
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
                            <p class="text-xs text-blue-600 font-medium">{{ auth()->user()->ctf_points ?? 0 }} CTF poin</p>
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
                            <div class="flex items-center space-x-2 mb-2">
                                <a href="{{ route('ctf.show', $ctf) }}" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <h1 class="text-2xl font-bold text-gray-900">Submissions CTF</h1>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($ctf->status === 'active') bg-green-100 text-green-800
                                        @elseif($ctf->status === 'draft') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($ctf->status) }}
                                    </span>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $ctf->name }}</h2>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('ctf.show', $ctf) }}" 
                               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Back to CTF
                            </a>
                            <a href="{{ route('ctf.leaderboard', $ctf) }}" 
                               class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                                <i class="fas fa-trophy mr-2"></i>Leaderboard
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6 bg-gray-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-4 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg">
                    <i class="fas fa-paper-plane text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_submissions'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-4 bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Correct Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['correct_submissions'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-4 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Users Solved</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['unique_users'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg">
                    <i class="fas fa-trophy text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Challenges</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_challenges'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- All Correct Submissions -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                Correct Submissions
            </h3>
            <p class="text-green-100 text-sm mt-2">Submissions yang berhasil diselesaikan (flag disensor untuk privacy)</p>
        </div>
    
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenge</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flag Submitted</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($submission->user->avatar)
                                    <img src="{{ asset('storage/' . $submission->user->avatar) }}" alt="{{ $submission->user->name }}" 
                                         class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-xs"></i>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->user->username ?? $submission->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $submission->user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $submission->challenge->title ?? 'Deleted Challenge' }}</div>
                            <div class="text-sm text-gray-500">{{ $submission->challenge->category ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-mono bg-gradient-to-r from-gray-100 to-gray-200 px-3 py-2 rounded-lg border max-w-xs">
                                {{ $submission->submitted_flag }}
                                @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->id === $submission->user_id))
                                    <span class="inline-block ml-2 px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">uncensored</span>
                                @else
                                    <span class="inline-block ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded-full">censored</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-green-600">+{{ $submission->points_earned ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $submission->submitted_at->format('d M Y, H:i') }}</div>
                            <div class="text-xs text-gray-400">{{ $submission->submitted_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 whitespace-nowrap text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-paper-plane text-4xl mb-4"></i>
                                <p class="text-sm font-medium">Belum ada submission yang benar</p>
                                <p class="text-xs text-gray-400 mt-1">Jadilah yang pertama menyelesaikan challenge ini!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($submissions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>

    <!-- Solvers by Challenge -->
    @if($submissionsByChallenge->count() > 0)
    <div class="mt-6 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-6">
            <h4 class="text-xl font-bold text-white flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <i class="fas fa-trophy text-white"></i>
                </div>
                Solvers by Challenge
            </h4>
            <p class="text-blue-100 text-sm mt-2">Daftar solvers untuk setiap challenge, diurutkan berdasarkan waktu solve</p>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                @foreach($submissionsByChallenge as $challengeId => $challengeSubmissions)
                    @php
                        $solversCount = $challengeSubmissions->count();
                        $firstBlood = $challengeSubmissions->first();
                        $challenge = $firstBlood->challenge ?? null;
                        $category = $challenge ? $challenge->getCategoryColor() : 'bg-gray-100 text-gray-800';
                    @endphp
                    
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Challenge Header -->
                        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <h5 class="text-lg font-bold text-gray-900">{{ $challenge->title ?? 'Unknown Challenge' }}</h5>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $category }}">
                                        {{ $challenge->category ?? 'Unknown' }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $challenge ? $challenge->getPointsColor() : 'bg-gray-100 text-gray-800' }}">
                                        {{ $challenge->points ?? 0 }} pts
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">{{ $solversCount }}</div>
                                    <div class="text-xs text-gray-500">{{ $solversCount === 1 ? 'solver' : 'solvers' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Solvers List -->
                        <div class="p-4">
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($challengeSubmissions as $index => $submission)
                                    @php
                                        $isFirstBlood = $index === 0;
                                    @endphp
                                    <div class="flex items-center justify-between p-3 {{ $isFirstBlood ? 'bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200' : 'bg-gray-50' }} rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                                         onclick="window.location='/ctf/{{ $ctf->id }}/user/{{ $submission->user->id }}'"
                                         title="Lihat profil {{ $submission->user->name }}">
                                        <div class="flex items-center">
                                            @if($isFirstBlood)
                                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-3 shadow-lg">
                                                    <i class="fas fa-crown text-white text-sm"></i>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-gray-600 text-sm font-bold">{{ $index + 1 }}</span>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center">
                                                @if($submission->user->avatar)
                                                    <img src="{{ asset('storage/' . $submission->user->avatar) }}" alt="{{ $submission->user->name }}" 
                                                         class="w-6 h-6 rounded-full object-cover mr-2">
                                                @else
                                                    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                                        <i class="fas fa-user text-gray-600 text-xs"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 flex items-center">
                                                        {{ $submission->user->username ?? $submission->user->name }}
                                                        @if($isFirstBlood)
                                                            <i class="fas fa-trophy text-yellow-500 ml-2" title="First Blood!"></i>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $submission->submitted_at->format('d M Y, H:i') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold text-green-600">+{{ $submission->points_earned }}</div>
                                            @if($isFirstBlood)
                                                <div class="text-xs text-yellow-600 font-medium">First Blood</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Privacy Notice -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Privacy Notice</h4>
                <p class="text-sm text-blue-700 mt-1">
                    Untuk menjaga privacy, flag yang ditampilkan di sini disensor. Hanya admin dan pemilik submission yang dapat melihat flag lengkap.
                    Hanya submissions yang benar yang ditampilkan untuk mencegah spoiler.
                </p>
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

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session("error") }}',
            confirmButtonText: 'OK'
        });
    </script>
    @endif
</body>
</html>
