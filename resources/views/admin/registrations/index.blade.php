<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Workshop - Cyber Infinity</title>
    
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
        /* DESKTOP: Force sidebar to show */
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
                width: 16rem !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                z-index: auto !important;
            }
            
            .flex.min-h-screen {
                display: flex !important;
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
        
        /* Force flex layout */
        .flex.min-h-screen {
            display: flex !important;
        }
        
        /* Debug - Force show sidebar */
        #sidebar {
            display: block !important;
        }
        
        @media (min-width: 1024px) {
            #sidebar {
                position: static !important;
                transform: none !important;
                width: 16rem !important;
                left: auto !important;
                top: auto !important;
                bottom: auto !important;
                right: auto !important;
            }
        }
    </style>

</head>
<body class="bg-white">
    <!-- Dashboard Container -->
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
                    
                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
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
        <div class="flex-1 lg:ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Registrasi Workshop</h1>
                            <p class="text-sm text-gray-600">Kelola dan approve registrasi peserta workshop</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button onclick="window.location.href='{{ route('workshops.index') }}'" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>Lihat Workshop
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Registration Content -->
            <main class="flex-1 p-6 bg-white">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div x-data="{ activeTab: {{ $workshops->first()->id ?? 0 }} }">
                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex overflow-x-auto" aria-label="Tabs">
                                @foreach($workshops as $index => $workshop)
                                <button @click="activeTab = {{ $workshop->id }}" 
                                    :class="{ 
                                        'border-blue-500 text-blue-600': activeTab === {{ $workshop->id }},
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== {{ $workshop->id }}
                                    }"
                                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200"
                                    @if($index === 0) x-init="activeTab = {{ $workshop->id }}" @endif>
                                    {{ $workshop->title }}
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                        {{ $workshop->registrations->count() }}
                                    </span>
                                </button>
                                @endforeach
                            </nav>
                        </div>

                        <!-- Table Content -->
                        @foreach($workshops as $workshop)
                        <div x-show="activeTab === {{ $workshop->id }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Daftar</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($workshop->registrations as $registration)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $registration->full_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $registration->class }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $registration->nis }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $registration->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                       ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       'bg-red-100 text-red-800') }}">
                                                    @if($registration->status === 'approved')
                                                        <i class="fas fa-check mr-1"></i>Diterima
                                                    @elseif($registration->status === 'pending')
                                                        <i class="fas fa-clock mr-1"></i>Pending
                                                    @else
                                                        <i class="fas fa-times mr-1"></i>Ditolak
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $registration->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if($registration->status === 'pending')
                                                <div class="flex justify-end space-x-2">
                                                    <form action="{{ route('admin.registrations.approve', $registration) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            <i class="fas fa-check mr-1"></i>Terima
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.registrations.reject', $registration) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            <i class="fas fa-times mr-1"></i>Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                                @else
                                                <span class="text-gray-400 text-xs">Sudah diproses</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 whitespace-nowrap text-center">
                                                <div class="text-gray-400">
                                                    <i class="fas fa-user-friends text-4xl mb-4"></i>
                                                    <p class="text-sm font-medium">Belum ada registrasi untuk workshop ini</p>
                                                    <p class="text-xs text-gray-400 mt-1">Peserta akan muncul di sini setelah mendaftar</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
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

        // ===== RESPONSIVE SIDEBAR FUNCTIONS =====
        
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
            
            // Force sidebar to show on desktop
            function forceSidebarShow() {
                if (window.innerWidth >= 1024) {
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar) {
                        sidebar.style.position = 'static';
                        sidebar.style.transform = 'translateX(0)';
                        sidebar.style.width = '16rem';
                        sidebar.style.display = 'block';
                        sidebar.style.visibility = 'visible';
                        sidebar.style.opacity = '1';
                        sidebar.style.left = 'auto';
                        sidebar.style.top = 'auto';
                        sidebar.style.bottom = 'auto';
                        sidebar.style.right = 'auto';
                        sidebar.style.zIndex = 'auto';
                        console.log('Sidebar forced to show');
                    }
                }
            }
            
            // Call force show after a short delay
            setTimeout(forceSidebarShow, 100);
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeResponsiveSidebar);
        } else {
            initializeResponsiveSidebar();
        }
    </script>
</body>
</html>