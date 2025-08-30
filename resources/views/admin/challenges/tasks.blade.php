<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Tasks - {{ $challenge->title }}</title>
    
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
        <aside id="sidebar" class="w-64 bg-white shadow-sm border-r border-gray-200 fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 lg:transform-none lg:static lg:inset-0">
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
                    
                    <a href="{{ route('challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-flag w-5 h-5 mr-3"></i>
                        Challenges
                    </a>
                    
                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                        Registrasi Workshop
                    </a>
                    
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        Kelola Challenges
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
                        <div class="flex items-center">
                            <a href="{{ route('admin.challenges.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Kelola Tasks</h1>
                                <p class="text-sm text-gray-600">Challenge: {{ $challenge->title }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button onclick="openAddTaskModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Task
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Tasks Content -->
            <main class="flex-1 p-6 bg-white">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <!-- Challenge Info -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="fas fa-tasks text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $tasks->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Active Tasks</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $tasks->where('is_active', true)->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-yellow-100 rounded-lg">
                                        <i class="fas fa-star text-yellow-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Poin</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $tasks->sum('points') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <i class="fas fa-eye text-purple-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Challenge Status</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($challenge->status) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks List -->
                    <div class="p-6">
                        @if($tasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($tasks as $task)
                                    <div class="border border-gray-200 rounded-lg p-4 {{ !$task->is_active ? 'opacity-50' : '' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start">
                                                <div class="w-8 h-8 {{ $task->is_active ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400' }} rounded-full flex items-center justify-center mr-3 mt-1">
                                                    <span class="text-sm font-medium">{{ $task->order }}</span>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $task->title }}</h3>
                                                    <p class="text-gray-700 mb-3">{{ $task->description }}</p>
                                                    
                                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-flag mr-1"></i>
                                                            <code class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $task->flag }}</code>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i class="fas fa-star mr-1 text-yellow-500"></i>
                                                            <span>{{ $task->points }} poin</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i class="fas fa-{{ $task->is_active ? 'check-circle text-green-500' : 'times-circle text-red-500' }} mr-1"></i>
                                                            <span>{{ $task->is_active ? 'Active' : 'Inactive' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button onclick="editTask({{ $task->id }})" 
                                                        class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <form action="{{ route('admin.challenges.tasks.destroy', $task) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus task ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition-colors duration-200">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-tasks text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada task</h3>
                                <p class="text-gray-600 mb-4">Tambahkan task pertama untuk challenge ini</p>
                                <button onclick="openAddTaskModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Tambah Task
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Tambah Task Baru</h2>
                    <button onclick="closeAddTaskModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.challenges.tasks.store', $challenge) }}" method="POST">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Task <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Task 1: Login tanpa password" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Jelaskan apa yang harus dilakukan user..." required></textarea>
                    </div>

                    <!-- Flag and Points -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="flag" class="block text-sm font-medium text-gray-700 mb-2">
                                Flag <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="flag" name="flag" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="CYBER{flag_here}" required>
                        </div>

                        <div>
                            <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                                Poin <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="points" name="points" min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="50" required>
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="order" name="order" min="1" value="{{ $tasks->count() + 1 }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Task akan dikerjakan berdasarkan urutan ini</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" onclick="closeAddTaskModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddTaskModal() {
            document.getElementById('addTaskModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddTaskModal() {
            document.getElementById('addTaskModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function editTask(taskId) {
            // Implementasi edit task (bisa menggunakan modal atau redirect ke halaman edit)
            Swal.fire({
                title: 'Edit Task',
                text: 'Fitur edit task akan segera tersedia',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }

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

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddTaskModal();
            }
        });

        // Close modal on outside click
        document.getElementById('addTaskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddTaskModal();
            }
        });
    </script>
</body>
</html>
