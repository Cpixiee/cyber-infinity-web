<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Kelola Challenges - Cyber Infinity</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/fih-logo.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/fih-logo.png')); ?>">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
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
                        <img src="<?php echo e(asset('images/fih-logo.png')); ?>" alt="FIH Logo" class="w-8 h-8 rounded-lg">
                        <h1 class="ml-3 text-xl font-bold text-gray-900">Cyber Infinity</h1>
                    </div>
                    <!-- Mobile Close Button -->
                    <button id="mobile-close-btn" class="block lg:!hidden p-1 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-home w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="<?php echo e(route('workshops.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
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
                            <a href="<?php echo e(route('challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-play w-4 h-4 mr-3"></i>
                                Lihat Challenges
                            </a>
                            <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola Challenges
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('admin.registrations.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                        Registrasi Workshop
                    </a>
                    
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                        Profile
                    </a>
                </nav>

                <!-- User Profile & Logout -->
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center mb-3">
                        <?php if(auth()->user()->avatar): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>" alt="<?php echo e(auth()->user()->name); ?>" 
                                 class="w-8 h-8 rounded-full object-cover sidebar-avatar">
                        <?php else: ?>
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600 text-sm"></i>
                            </div>
                        <?php endif; ?>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(ucfirst(auth()->user()->role)); ?></p>
                            <p class="text-xs text-blue-600 font-medium"><?php echo e(auth()->user()->points ?? 0); ?> poin</p>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                        <?php echo csrf_field(); ?>
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
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Challenges</h1>
                            <p class="text-sm text-gray-600">Buat dan kelola challenge rooms untuk peserta</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo e(route('admin.challenges.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Buat Challenge
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Challenge Content -->
            <main class="flex-1 p-6 bg-white">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <!-- Stats Cards -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="fas fa-flag text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Challenges</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($challenges->total()); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Active</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($challenges->where('status', 'active')->count()); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-yellow-100 rounded-lg">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Draft</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($challenges->where('status', 'draft')->count()); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <i class="fas fa-users text-purple-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Submissions</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($challenges->sum('submissions_count')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenge</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kesulitan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submissions</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-flag text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($challenge->title); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e(Str::limit($challenge->description, 50)); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($challenge->getCategoryColor()); ?>">
                                            <?php echo e($challenge->category); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($challenge->getDifficultyColor()); ?>">
                                            <?php echo e($challenge->difficulty); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($challenge->points); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($challenge->tasks->count()); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php echo e($challenge->status === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($challenge->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800')); ?>">
                                            <?php echo e(ucfirst($challenge->status)); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($challenge->submissions_count ?? 0); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="<?php echo e(route('admin.challenges.tasks', $challenge)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-purple-600 hover:bg-purple-700">
                                                <i class="fas fa-tasks mr-1"></i>Tasks
                                            </a>
                                            <a href="<?php echo e(route('admin.challenges.submissions', $challenge)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                <i class="fas fa-paper-plane mr-1"></i>Submissions
                                            </a>
                                            <a href="<?php echo e(route('admin.challenges.edit', $challenge)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="<?php echo e(route('admin.challenges.destroy', $challenge)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus challenge ini?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-12 whitespace-nowrap text-center">
                                        <div class="text-gray-400">
                                            <i class="fas fa-flag text-4xl mb-4"></i>
                                            <p class="text-sm font-medium">Belum ada challenge</p>
                                            <p class="text-xs text-gray-400 mt-1">Buat challenge pertama Anda</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($challenges->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-gray-200">
                        <?php echo e($challenges->links()); ?>

                    </div>
                    <?php endif; ?>
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

        // Show success message if exists
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo e(session("success")); ?>',
                showConfirmButton: false,
                timer: 3000
            });
        <?php endif; ?>

        // Show error message if exists
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo e(session("error")); ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

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
<?php /**PATH C:\laragon\www\cyber-infinity-web\resources\views/admin/challenges/index.blade.php ENDPATH**/ ?>