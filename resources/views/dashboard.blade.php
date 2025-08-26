<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Cyber Infinity</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Check if user needs username setup and show popup -->
    @if(auth()->user()->needsUsernameSetup())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show username setup popup
            showUsernameSetupModal();
        });
    </script>
    @endif
    
    <!-- Chart.js - try multiple CDNs for reliability -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" 
            onerror="this.onerror=null; this.src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js'"></script>
    
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
    <!-- Dashboard Container -->
    <!-- Mobile Menu Button - ONLY for mobile -->
    <button id="mobile-menu-btn" class="block lg:!hidden fixed top-4 left-4 z-[60] p-2 bg-white rounded-lg shadow-md border border-gray-200">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Mobile Overlay - ONLY for mobile -->
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
                    <!-- Mobile Close Button - ONLY for mobile -->
                    <button id="mobile-close-btn" class="block lg:!hidden p-1 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
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
                            @if(auth()->user()->role === 'admin')
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
                    
                    @if(auth()->user()->role === 'admin')
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
                            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                            <p class="text-sm text-gray-600">Selamat datang kembali, {{ auth()->user()->name }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notification Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 relative">
                                    <i class="fas fa-bell"></i>
                                    @if(auth()->user()->unreadNotificationsCount() > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ auth()->user()->unreadNotificationsCount() }}
                                    </span>
                                    @endif
                                </button>
                                
                                <!-- Notification Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <div class="px-4 py-2 border-b border-gray-200">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                        </div>
                                        
                                        <div class="max-h-64 overflow-y-auto">
                                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                            <div class="px-4 py-3 hover:bg-gray-50 {{ $notification->isRead() ? 'opacity-60' : 'bg-blue-50' }} notification-item" data-id="{{ $notification->id }}">
                                                <div class="flex items-start">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                        <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    <div class="ml-2 flex items-center space-x-1">
                                                        @if(!$notification->isRead())
                                                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                                        @endif
                                                        <button onclick="deleteNotification({{ $notification->id }})" class="text-red-400 hover:text-red-600 p-1">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="px-4 py-3 text-center text-gray-500">
                                                <p class="text-sm">Tidak ada notifikasi</p>
                                            </div>
                                            @endforelse
                                        </div>
                                        
                                        @if(auth()->user()->notifications()->count() > 0)
                                        <div class="px-4 py-2 border-t border-gray-200 flex justify-between">
                                            <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-check-double mr-1"></i>Baca Semua
                                            </button>
                                            <button onclick="deleteAllNotifications()" class="text-sm text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash mr-1"></i>Hapus Semua
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-cog"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 p-6 bg-white">
                <!-- Dashboard Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @php
                        $totalUsers = \App\Models\User::count();
                        $totalWorkshops = \App\Models\Workshop::count();
                        
                        // 1. User Aktif Bulan Ini: Users who are currently active (logged in recently or have sessions)
                        // Simulate active users - in real app, you'd track last_activity or sessions
                        $currentActiveUsers = \App\Models\User::where('updated_at', '>=', now()->subHours(24))->count(); // Active in last 24h
                        $lastDayActiveUsers = \App\Models\User::whereBetween('updated_at', [now()->subDays(2), now()->subDays(1)])->count();
                        
                        // Growth calculation for active users
                        $activeUserGrowth = $lastDayActiveUsers > 0 ? (($currentActiveUsers - $lastDayActiveUsers) / $lastDayActiveUsers) * 100 : ($currentActiveUsers > 0 ? 100 : 0);
                        
                        // 2. Aktivitas Bulanan: User registrations (sign ups) to the website per month
                        $monthlyUserRegistrations = [];
                        for ($i = 1; $i <= 12; $i++) {
                            $monthlyUserRegistrations[] = \App\Models\User::whereMonth('created_at', $i)
                                ->whereYear('created_at', now()->year)->count();
                        }
                        
                        // Current month vs last month user registrations
                        $currentMonthRegistrations = \App\Models\User::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)->count();
                        $lastMonthRegistrations = \App\Models\User::whereMonth('created_at', now()->subMonth()->month)
                            ->whereYear('created_at', now()->subMonth()->year)->count();
                        
                        // Calculate user registration growth
                        $userGrowth = $lastMonthRegistrations > 0 ? (($currentMonthRegistrations - $lastMonthRegistrations) / $lastMonthRegistrations) * 100 : ($currentMonthRegistrations > 0 ? 100 : 0);
                        
                        // Workshop stats
                        $workshopsActive = \App\Models\Workshop::where('status', 'active')->count();
                        $lastMonthWorkshops = \App\Models\Workshop::whereMonth('created_at', now()->subMonth()->month)
                            ->whereYear('created_at', now()->subMonth()->year)->count();
                        $workshopGrowth = $lastMonthWorkshops > 0 ? (($totalWorkshops - $lastMonthWorkshops) / $lastMonthWorkshops) * 100 : ($totalWorkshops > 0 ? 100 : -9.05);
                        
                        // 3. Performance Speedometer: Based on user activity (login frequency + workshop participation)
                        $totalScoreUsers = 0;
                        $userCount = 0;
                        
                        foreach(\App\Models\User::all() as $user) {
                            $userScore = 0;
                            $userCount++;
                            
                            // Score based on login frequency (last 30 days activity)
                            $recentActivity = $user->updated_at >= now()->subDays(7) ? 50 : 0; // 50 points for recent activity
                            
                            // Score based on workshop participation
                            $workshopParticipation = \App\Models\WorkshopRegistration::where('email', $user->email)
                                ->where('status', 'approved')->count() * 25; // 25 points per approved workshop
                            
                            // Cap at 100
                            $userScore = min(100, $recentActivity + $workshopParticipation);
                            $totalScoreUsers += $userScore;
                        }
                        
                        // Average performance percentage
                        $performancePercentage = $userCount > 0 ? $totalScoreUsers / $userCount : 0;
                        
                        // Performance growth (compare with simulated previous period)
                        $previousPerformance = $performancePercentage > 50 ? $performancePercentage - 20 : $performancePercentage + 10;
                        $performanceGrowth = $previousPerformance > 0 ? (($performancePercentage - $previousPerformance) / $previousPerformance) * 100 : 0;
                    @endphp
                    
                    <!-- Anggota Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-blue-50">
                                    <i class="fas fa-users text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Anggota</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $userGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $userGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                    {{ number_format(abs($userGrowth), 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Workshop Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-green-50">
                                    <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Workshop</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalWorkshops }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $workshopsActive > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    9.05%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- User Aktif Sekarang Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-purple-50">
                                    <i class="fas fa-user-check text-purple-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">User Aktif Sekarang</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $currentActiveUsers }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activeUserGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $activeUserGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                    {{ number_format(abs($activeUserGrowth), 0) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Aktivitas Bulanan Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Aktivitas Bulanan</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        <div class="h-64">
                            <canvas id="monthlyActivityChart"></canvas>
                        </div>
                    </div>

                    <!-- Performance Speedometer Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Performance User</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        <div class="text-center mb-4">
                            <p class="text-sm text-gray-600">Aktivitas & partisipasi user (speedometer)</p>
                        </div>
                        <div class="flex items-center justify-center mb-6">
                            <div class="relative w-48 h-48">
                                <canvas id="performanceChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-gray-900">{{ number_format($performancePercentage, 0) }}%</div>
                                        <div class="text-sm {{ $performanceGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $performanceGrowth >= 0 ? '+' : '' }}{{ number_format($performanceGrowth, 0) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 text-center">
                            Performance berdasarkan login & workshop. {{ $currentActiveUsers }} user aktif dari {{ $totalUsers }} total user. 
                            @if($performancePercentage >= 80)
                                Excellent performance!
                            @elseif($performancePercentage >= 60) 
                                Good performance!
                            @elseif($performancePercentage >= 40)
                                Average performance
                            @else
                                Needs improvement
                            @endif
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900">Target</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900">Tercapai</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900">Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                        <p class="text-sm text-gray-600 mt-1">Workshop dan registrasi terkini</p>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $recentWorkshops = \App\Models\Workshop::latest()->take(3)->get();
                            $recentRegistrations = \App\Models\WorkshopRegistration::latest()->take(5)->get();
                        @endphp
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Recent Workshops -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Workshop Terbaru</h3>
                                <div class="space-y-3">
                                    @forelse($recentWorkshops as $workshop)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <i class="fas fa-graduation-cap text-blue-600"></i>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $workshop->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $workshop->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $workshop->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($workshop->status) }}
                                        </span>
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-500 text-center py-4">Belum ada workshop</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Recent Registrations -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Registrasi Terbaru</h3>
                                <div class="space-y-3">
                                    @forelse($recentRegistrations as $registration)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="p-2 bg-purple-100 rounded-lg">
                                            <i class="fas fa-user text-purple-600"></i>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $registration->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $registration->workshop->title ?? 'Workshop tidak ditemukan' }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-500 text-center py-4">Belum ada registrasi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Function to initialize charts with limited retries
        let chartRetryCount = 0;
        const maxRetries = 10;
        
        function initializeCharts() {
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                if (chartRetryCount < maxRetries) {
                    chartRetryCount++;
                    console.error(`Chart.js is not loaded! Retry ${chartRetryCount}/${maxRetries} in 500ms...`);
                    setTimeout(initializeCharts, 500);
                } else {
                    console.error('Chart.js failed to load after ' + maxRetries + ' attempts. Charts will not be available.');
                    // Show fallback message to user
                    document.getElementById('monthlyActivityChart')?.parentNode?.insertAdjacentHTML('beforeend', '<p class="text-center text-red-500 text-sm mt-4">Chart gagal dimuat. Refresh halaman untuk mencoba lagi.</p>');
                    document.getElementById('performanceChart')?.parentNode?.insertAdjacentHTML('beforeend', '<p class="text-center text-red-500 text-sm mt-4">Chart gagal dimuat. Refresh halaman untuk mencoba lagi.</p>');
                }
                return;
            }
            
            console.log('Dashboard script loaded successfully');
            console.log('Chart.js version:', Chart.version);
            
            // Debug data
            console.log('Monthly data:', @json($monthlyUserRegistrations));
            console.log('Performance percentage:', {{ $performancePercentage }});
            
            // ===== CHART INITIALIZATION =====
            
            // Monthly Activity Chart (Bar Chart) - User registrations to website per month
            const monthlyActivityCtx = document.getElementById('monthlyActivityChart');
            if (!monthlyActivityCtx) {
                console.error('Monthly chart canvas not found');
                return;
            }
            
            const monthlyUserData = @json($monthlyUserRegistrations);
            console.log('Rendering monthly chart with data:', monthlyUserData);
            
            // Ensure we have valid data to display
            let chartData = monthlyUserData;
            if (!Array.isArray(chartData) || chartData.length === 0) {
                console.log('Using sample data for monthly chart');
                chartData = [0, 1, 0, 1, 0, 1, 0, 2, 0, 1, 0, 0]; // Sample data
            }
            const maxValue = Math.max(...chartData) || 5;
            
            const monthlyActivityChart = new Chart(monthlyActivityCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'User Daftar ke Website',
                        data: chartData,
                        backgroundColor: '#3B82F6',
                        borderRadius: 4,
                        maxBarThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return `${context.parsed.y} user daftar`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: '#F3F4F6'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6B7280',
                                stepSize: 1
                            },
                            max: Math.max(maxValue + 2, 5)
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });

            // Performance Speedometer Chart (Doughnut Chart)
            const performanceCtx = document.getElementById('performanceChart');
            if (!performanceCtx) {
                console.error('Performance chart canvas not found');
                return;
            }
            
            let performancePercentage = {{ $performancePercentage }};
            if (isNaN(performancePercentage) || performancePercentage < 0) {
                console.log('Using sample performance data');
                performancePercentage = 50; // Default sample
            }
            const remainingPerformance = 100 - performancePercentage;
            console.log('Rendering performance chart with:', performancePercentage + '%');
            
            // Color based on performance level (speedometer style)
            let performanceColor = '#EF4444'; // Red for low performance
            if (performancePercentage >= 80) {
                performanceColor = '#22C55E'; // Green for excellent
            } else if (performancePercentage >= 60) {
                performanceColor = '#3B82F6'; // Blue for good  
            } else if (performancePercentage >= 40) {
                performanceColor = '#F59E0B'; // Yellow for average
            }
            
            const performanceChart = new Chart(performanceCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [performancePercentage, remainingPerformance],
                        backgroundColor: [performanceColor, '#E5E7EB'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.dataIndex === 0) {
                                        return `Performance: ${performancePercentage.toFixed(1)}%`;
                                    } else {
                                        return `Potential: ${remainingPerformance.toFixed(1)}%`;
                                    }
                                }
                            }
                        }
                    }
                }
            });
            
        } // End of initializeCharts function

        // Multiple loading methods to ensure Chart.js loads
        function tryInitCharts() {
            initializeCharts();
        }

        // Method 1: DOMContentLoaded
        document.addEventListener('DOMContentLoaded', tryInitCharts);
        
        // Method 2: Window load (fallback)
        window.addEventListener('load', function() {
            if (chartRetryCount > 0) {
                console.log('Trying chart initialization on window load...');
                chartRetryCount = 0; // Reset counter
                tryInitCharts();
            }
        });
        
        // Method 3: Manual load Chart.js if CDN fails
        if (typeof Chart === 'undefined') {
            console.log('Loading Chart.js manually...');
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/chart.js@3.9.1/dist/chart.min.js';
            script.onload = function() {
                console.log('Chart.js loaded manually!');
                tryInitCharts();
            };
            script.onerror = function() {
                console.error('Failed to load Chart.js from all sources');
            };
            document.head.appendChild(script);
        }

        // ===== RESPONSIVE SIDEBAR FUNCTIONS =====
        
        function initializeResponsiveSidebar() {
            console.log('Initializing responsive sidebar...');
            
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileCloseBtn = document.getElementById('mobile-close-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            // Check if mobile
            function isMobile() {
                return window.innerWidth < 1024;
            }
            
            // Initialize correct state
            function setupSidebarState() {
                if (isMobile()) {
                    // Mobile: sidebar hidden by default
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    console.log('Mobile mode: sidebar closed');
                } else {
                    // Desktop: ensure sidebar is visible 
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    console.log('Desktop mode: sidebar visible');
                }
            }
            
            // Open mobile menu (only works on mobile)
            function openMobileMenu() {
                if (!isMobile()) return;
                console.log('Opening mobile sidebar');
                sidebar?.classList.add('mobile-open');
                overlay?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            
            // Close mobile menu (only works on mobile)
            function closeMobileMenu() {
                if (!isMobile()) return;
                console.log('Closing mobile sidebar');
                sidebar?.classList.remove('mobile-open');
                overlay?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            // Event listeners
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
            
            // Escape key
            document.addEventListener('keydown', function(e) {
                if (!isMobile()) return;
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                setupSidebarState();
            });
            
            // Initial setup
            setupSidebarState();
        }
        
        // Initialize
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeResponsiveSidebar);
        } else {
            initializeResponsiveSidebar();
        }

        // ===== NOTIFICATION FUNCTIONS =====
        
        // Mark all notifications as read
        async function markAllAsRead() {
            try {
                const response = await fetch('{{ route("notifications.markAllRead") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('bg-blue-50');
                        item.classList.add('opacity-60');
                        const badge = item.querySelector('.w-2.h-2');
                        if (badge) badge.remove();
                    });

                    // Update badge count
                    const badgeElement = document.querySelector('.absolute.-top-1');
                    if (badgeElement) badgeElement.remove();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses permintaan'
                });
            }
        }

        // Delete individual notification
        async function deleteNotification(notificationId) {
            const result = await Swal.fire({
                title: 'Hapus Notifikasi?',
                text: 'Notifikasi ini akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ url('/notifications') }}/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const responseData = await response.json();
                    
                    if (responseData.success) {
                        // Remove notification from UI
                        const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
                        if (notificationElement) {
                            notificationElement.remove();
                        }

                        // Update badge count
                        updateNotificationBadge();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: responseData.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus notifikasi'
                    });
                }
            }
        }

        // Delete all notifications
        async function deleteAllNotifications() {
            const result = await Swal.fire({
                title: 'Hapus Semua Notifikasi?',
                text: 'Semua notifikasi akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus Semua',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route("notifications.deleteAll") }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const responseData = await response.json();
                    
                    if (responseData.success) {
                        // Clear all notifications from UI
                        const notificationContainer = document.querySelector('.max-h-64');
                        notificationContainer.innerHTML = '<div class="px-4 py-3 text-center text-gray-500"><p class="text-sm">Tidak ada notifikasi</p></div>';

                        // Remove badge
                        const badgeElement = document.querySelector('.absolute.-top-1');
                        if (badgeElement) badgeElement.remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: responseData.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus notifikasi'
                    });
                }
            }
        }

        // Update notification badge count
        function updateNotificationBadge() {
            const remainingNotifications = document.querySelectorAll('.notification-item').length;
            const badgeElement = document.querySelector('.absolute.-top-1');
            
            if (remainingNotifications === 0 && badgeElement) {
                badgeElement.remove();
            } else if (badgeElement) {
                badgeElement.textContent = remainingNotifications;
            }
        }

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
        
        // Function to show username setup modal
        function showUsernameSetupModal() {
            Swal.fire({
                title: '<i class="fas fa-user-plus text-green-500 text-3xl mb-2"></i><br>Selamat Datang!',
                html: `
                    <div class="text-left">
                        <p class="text-gray-600 mb-4">Untuk melengkapi profil Anda, silakan atur username dan nama (opsional):</p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap (Opsional)</label>
                                <input type="text" id="swal-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="Masukkan nama lengkap" value="{{ auth()->user()->name }}" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                                <input type="text" id="swal-username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="Masukkan username unik" required />
                                <p class="text-xs text-gray-500 mt-1">Username akan digunakan untuk profil publik dan leaderboard</p>
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-check mr-2"></i>Simpan',
                confirmButtonColor: '#10b981',
                allowOutsideClick: false,
                allowEscapeKey: false,
                width: '500px',
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-xl font-bold text-gray-900',
                    confirmButton: 'px-6 py-2 rounded-lg font-semibold',
                },
                preConfirm: () => {
                    const name = document.getElementById('swal-name').value.trim();
                    const username = document.getElementById('swal-username').value.trim();
                    
                    if (!username) {
                        Swal.showValidationMessage('Username wajib diisi!');
                        return false;
                    }
                    
                    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                        Swal.showValidationMessage('Username hanya boleh mengandung huruf, angka, dan underscore!');
                        return false;
                    }
                    
                    return { name: name, username: username };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the data
                    submitUsernameSetup(result.value.name, result.value.username);
                }
            });
        }

        // Function to submit username setup
        function submitUsernameSetup(name, username) {
            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send data to server
            fetch('{{ route("profile.setup-username") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: name,
                    username: username
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981',
                    }).then(() => {
                        // Reload page to reflect changes
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat menyimpan data.',
                        showConfirmButton: true,
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#ef4444',
                    }).then(() => {
                        // Show the modal again
                        showUsernameSetupModal();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                    showConfirmButton: true,
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#ef4444',
                }).then(() => {
                    // Show the modal again
                    showUsernameSetupModal();
                });
            });
        }
    </script>
</body>
</html>