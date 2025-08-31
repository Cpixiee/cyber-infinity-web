<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Workshop - Cyber Infinity</title>
    
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
                    
                    <a href="<?php echo e(route('workshops.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
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
                            <a href="<?php echo e(route('challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-play w-4 h-4 mr-3"></i>
                                Lihat Challenges
                            </a>
                            <?php if(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola Challenges
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- CTF Events Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-trophy w-5 h-5 mr-3"></i>
                                <span>CTF Events</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                            <a href="<?php echo e(route('ctf.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-play w-4 h-4 mr-3"></i>
                                Lihat CTF Events
                            </a>
                            <?php if(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.ctf.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola CTF Events
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if(auth()->user()->role === 'admin'): ?>
                    <a href="<?php echo e(route('admin.registrations.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                        Registrasi Workshop
                    </a>
                    <?php endif; ?>
                    
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
        <div class="flex-1 lg:!ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Workshop</h1>
                            <p class="text-sm text-gray-600">Jelajahi dan daftar workshop yang tersedia</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <?php if(auth()->user()->role === 'admin'): ?>
                            <button onclick="window.location.href='<?php echo e(route('workshops.create')); ?>'" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Workshop
                            </button>
                            <?php endif; ?>
                            
                            <!-- Notification Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 relative">
                                    <i class="fas fa-bell"></i>
                                    <?php if(auth()->user()->unreadNotificationsCount() > 0): ?>
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        <?php echo e(auth()->user()->unreadNotificationsCount()); ?>

                                    </span>
                                    <?php endif; ?>
                                </button>
                                
                                <!-- Notification Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <div class="px-4 py-2 border-b border-gray-200">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                        </div>
                                        
                                        <div class="max-h-64 overflow-y-auto">
                                            <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="px-4 py-3 hover:bg-gray-50 <?php echo e($notification->isRead() ? 'opacity-60' : 'bg-blue-50'); ?> notification-item" data-id="<?php echo e($notification->id); ?>">
                                                <div class="flex items-start">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900"><?php echo e($notification->title); ?></p>
                                                        <p class="text-sm text-gray-600"><?php echo e($notification->message); ?></p>
                                                        <p class="text-xs text-gray-400 mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                                                    </div>
                                                    <div class="ml-2 flex items-center space-x-1">
                                                        <?php if(!$notification->isRead()): ?>
                                                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                                        <?php endif; ?>
                                                        <button onclick="deleteNotification(<?php echo e($notification->id); ?>)" class="text-red-400 hover:text-red-600 p-1">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="px-4 py-3 text-center text-gray-500">
                                                <p class="text-sm">Tidak ada notifikasi</p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if(auth()->user()->notifications()->count() > 0): ?>
                                        <div class="px-4 py-2 border-t border-gray-200 flex justify-between">
                                            <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-check-double mr-1"></i>Baca Semua
                                            </button>
                                            <button onclick="deleteAllNotifications()" class="text-sm text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash mr-1"></i>Hapus Semua
                                            </button>
                                        </div>
                                        <?php endif; ?>
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

            <!-- Workshop Content -->
            <main class="flex-1 p-6 bg-white">
                <!-- Workshop Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-blue-50">
                                <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Workshop</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo e($workshops->count()); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-green-50">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Workshop Aktif</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo e($workshops->where('status', 'active')->count()); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php
                        $userRegistration = null;
                        if (auth()->check()) {
                            $userRegistration = \App\Models\WorkshopRegistration::where(function($query) {
                                $query->where('email', auth()->user()->email);
                                if (auth()->user()->nis) {
                                    $query->orWhere('nis', auth()->user()->nis);
                                }
                            })->first();
                        }
                    ?>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-purple-50">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Status Pendaftaran</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    <?php if($userRegistration): ?>
                                        <?php if($userRegistration->status === 'approved'): ?>
                                            <span class="text-green-600">Diterima</span>
                                        <?php elseif($userRegistration->status === 'pending'): ?>
                                            <span class="text-yellow-600">Pending</span>
                                        <?php else: ?>
                                            <span class="text-red-600">Ditolak</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">Belum Daftar</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workshop Grid -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Workshop</h2>
                        <p class="text-sm text-gray-600 mt-1">Workshop yang tersedia untuk didaftarkan</p>
                    </div>
                    
                    <div class="p-6">
                        <?php if($workshops->isEmpty()): ?>
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-graduation-cap text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada workshop</h3>
                            <p class="text-gray-500 mb-4">Saat ini belum ada workshop yang tersedia</p>
                            <?php if(auth()->user()->role === 'admin'): ?>
                            <button onclick="window.location.href='<?php echo e(route('workshops.create')); ?>'" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Workshop Pertama
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $workshops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workshop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                <!-- Workshop Header -->
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2"><?php echo e($workshop->title); ?></h3>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($workshop->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e(ucfirst($workshop->status)); ?>

                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-3"><?php echo e($workshop->description); ?></p>
                                    
                                    <!-- Activity Type Badge -->
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag text-blue-600 w-3 h-3 mr-1"></i>
                                            <?php echo e(ucfirst($workshop->activity_type)); ?>

                                        </span>
                                    </div>
                                    
                                    <!-- Workshop Details -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-calendar text-gray-400 w-4 mr-2"></i>
                                            <span><?php echo e($workshop->start_date->format('d M Y')); ?> - <?php echo e($workshop->end_date->format('d M Y')); ?></span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-clock text-gray-400 w-4 mr-2"></i>
                                            <span><?php echo e($workshop->start_time ? \Carbon\Carbon::parse($workshop->start_time)->format('H:i') : 'TBD'); ?> (<?php echo e(number_format($workshop->duration, 1)); ?> jam)</span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt text-gray-400 w-4 mr-2"></i>
                                            <span><?php echo e($workshop->location ?: 'Location TBD'); ?></span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-users text-gray-400 w-4 mr-2"></i>
                                            <?php
                                                $approvedCount = $workshop->registrations()->where('status', 'approved')->count();
                                                $pendingCount = $workshop->registrations()->where('status', 'pending')->count();
                                            ?>
                                            <span><?php echo e($approvedCount); ?>/<?php echo e($workshop->target_participants); ?> peserta (<?php echo e($pendingCount); ?> pending)</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Registration Status and Actions -->
                                    <div class="pt-4 border-t border-gray-100">
                                        <?php
                                            $thisWorkshopRegistration = $workshop->getRegistrationForUser(auth()->user());
                                        ?>

                                        <?php if($thisWorkshopRegistration): ?>
                                            <!-- User is registered for this workshop -->
                                            <div class="space-y-2">
                                                <button disabled class="w-full px-4 py-2 rounded-lg text-sm font-medium
                                                    <?php echo e($thisWorkshopRegistration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($thisWorkshopRegistration->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')); ?>">
                                                    <?php if($thisWorkshopRegistration->status === 'pending'): ?>
                                                        <i class="fas fa-clock mr-2"></i>Menunggu Persetujuan
                                                    <?php elseif($thisWorkshopRegistration->status === 'approved'): ?>
                                                        <i class="fas fa-check-circle mr-2"></i>Pendaftaran Diterima
                                                    <?php else: ?>
                                                        <i class="fas fa-times-circle mr-2"></i>Pendaftaran Ditolak
                                                    <?php endif; ?>
                                                </button>
                                                <p class="text-xs text-center text-gray-500">Anda terdaftar di workshop ini</p>
                                            </div>
                                        <?php elseif($userRegistration): ?>
                                            <!-- User is registered for another workshop -->
                                            <div class="space-y-2">
                                                <button disabled class="w-full px-4 py-2 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium">
                                                    <i class="fas fa-ban mr-2"></i>Tidak Dapat Mendaftar
                                                </button>
                                                <p class="text-xs text-center text-gray-500">Anda sudah terdaftar di: <?php echo e($userRegistration->workshop->title); ?></p>
                                            </div>
                                        <?php else: ?>
                                            <!-- User can register -->
                                            <?php if($workshop->hasAvailableSlots()): ?>
                                                <button onclick="openRegistrationModal(<?php echo e($workshop->id); ?>)" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-user-plus mr-2"></i>Daftar Workshop
                                                </button>
                                            <?php else: ?>
                                                <button disabled class="w-full px-4 py-2 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium">
                                                    <i class="fas fa-users mr-2"></i>Workshop Penuh
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- Admin Actions -->
                                        <?php if(auth()->user()->role === 'admin'): ?>
                                        <div class="flex gap-2 mt-3">
                                            <button onclick="editWorkshop(<?php echo e($workshop->id); ?>)" class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium hover:bg-yellow-200 transition-colors duration-200">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <button onclick="deleteWorkshop(<?php echo e($workshop->id); ?>)" class="flex-1 px-3 py-2 bg-red-100 text-red-800 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Registration Modal -->
    <div id="registrationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Workshop</h3>
                    <button onclick="closeRegistrationModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="registrationForm" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="workshop_id" name="workshop_id">
                    
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="full_name" name="full_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <input type="text" id="class" name="class" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                        <input type="text" id="nis" name="nis" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo e(auth()->user()->email); ?>">
                    </div>
                    
                    <!-- Agreements -->
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <input type="checkbox" id="agreement_1" name="agreement_1" required class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="agreement_1" class="ml-2 text-sm text-gray-600">
                                Saya setuju untuk mengikuti workshop dengan komitmen penuh
                            </label>
                        </div>
                        
                        <div class="flex items-start">
                            <input type="checkbox" id="agreement_2" name="agreement_2" required class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="agreement_2" class="ml-2 text-sm text-gray-600">
                                Saya bersedia mengikuti seluruh sesi workshop yang telah dijadwalkan
                            </label>
                        </div>
                        
                        <div class="flex items-start">
                            <input type="checkbox" id="agreement_3" name="agreement_3" required class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="agreement_3" class="ml-2 text-sm text-gray-600">
                                Saya memahami bahwa pendaftaran ini mengikat dan tidak dapat dibatalkan
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeRegistrationModal()" 
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentWorkshopId = null;

        // ===== NOTIFICATION FUNCTIONS =====
        
        // Mark all notifications as read
        async function markAllAsRead() {
            try {
                const response = await fetch('<?php echo e(route("notifications.markAllRead")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
                    const response = await fetch(`<?php echo e(url('/notifications')); ?>/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
                    const response = await fetch('<?php echo e(route("notifications.deleteAll")); ?>', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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

        // ===== WORKSHOP FUNCTIONS =====
        
        function openRegistrationModal(workshopId) {
            currentWorkshopId = workshopId;
            document.getElementById('workshop_id').value = workshopId;
            document.getElementById('registrationModal').classList.remove('hidden');
            document.getElementById('registrationModal').classList.add('flex');
        }

        function closeRegistrationModal() {
            document.getElementById('registrationModal').classList.add('hidden');
            document.getElementById('registrationModal').classList.remove('flex');
            document.getElementById('registrationForm').reset();
            currentWorkshopId = null;
        }

        function editWorkshop(workshopId) {
            window.location.href = `/workshops/${workshopId}/edit`;
        }

        async function deleteWorkshop(workshopId) {
            const result = await Swal.fire({
                title: 'Hapus Workshop?',
                text: 'Workshop yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                // Create form dynamically to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/workshops/${workshopId}`;
                
                // Add CSRF token
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfField);
                
                // Add METHOD field for DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                // Add to body and submit
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Registration form submission
        document.getElementById('registrationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                Swal.fire({
                    title: 'Memproses Pendaftaran...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(`<?php echo e(url('/workshops')); ?>/${currentWorkshopId}/register`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        closeRegistrationModal();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Terjadi kesalahan saat mendaftar'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses pendaftaran'
                });
            }
        });

        // Close modal when clicking outside
        document.getElementById('registrationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegistrationModal();
            }
        });

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
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeResponsiveSidebar);
        } else {
            initializeResponsiveSidebar();
        }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\cyber-infinity\resources\views/workshops/index.blade.php ENDPATH**/ ?>