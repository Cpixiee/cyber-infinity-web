<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Edit Challenge - Cyber Infinity</title>
    
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
                        <img src="<?php echo e(asset('images/fih-logo.png')); ?>" alt="FIH Logo" class="w-8 h-8 rounded-lg">
                        <h1 class="ml-3 text-xl font-bold text-gray-900">Cyber Infinity</h1>
                    </div>
                    <!-- Mobile Close Button -->
                    <button id="mobile-close-btn" class="lg:hidden p-1 text-gray-400 hover:text-gray-600">
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
                    
                    <a href="<?php echo e(route('challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-flag w-5 h-5 mr-3"></i>
                        Challenges
                    </a>
                    
                    <a href="<?php echo e(route('admin.registrations.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                        Registrasi Workshop
                    </a>
                    
                    <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        Kelola Challenges
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
        <div class="flex-1 lg:ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Challenge</h1>
                            <p class="text-sm text-gray-600">Edit informasi challenge: <?php echo e($challenge->title); ?></p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo e(route('admin.challenges.tasks', $challenge)); ?>" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                <i class="fas fa-tasks mr-2"></i>Kelola Tasks
                            </a>
                            <a href="<?php echo e(route('admin.challenges.index')); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
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
                            <form action="<?php echo e(route('admin.challenges.update', $challenge)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                
                                <!-- Title -->
                                <div class="mb-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Challenge <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" value="<?php echo e(old('title', $challenge->title)); ?>" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Contoh: SQL Injection 101" required>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Description -->
                                <div class="mb-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="description" name="description" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              placeholder="Belajar dasar SQL Injection dengan login bypass..." required><?php echo e(old('description', $challenge->description)); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Category and Difficulty -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div x-data="{ 
                                        open: false, 
                                        selected: '<?php echo e(old('category', $challenge->category)); ?>', 
                                        custom: '', 
                                        showCustom: <?php echo e(in_array(old('category', $challenge->category), $categories) ? 'false' : 'true'); ?>,
                                        categories: ['Web', 'Crypto', 'Forensic', 'OSINT', 'Reverse', 'Pwn', 'Linux', 'Root', 'Network', 'Mobile', 'Hardware']
                                    }" 
                                    x-init="if (showCustom) custom = selected" 
                                    class="relative">
                                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        
                                        <!-- Hidden input for form submission -->
                                        <input type="hidden" name="category" :value="showCustom ? custom : selected" required>
                                        
                                        <!-- Dropdown Button -->
                                        <button type="button" @click="open = !open" 
                                                class="w-full px-3 py-2 text-left border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex items-center justify-between <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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
                                        
                                        <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <p class="mt-1 text-xs text-gray-500">Pilih dari kategori yang tersedia atau buat kategori custom</p>
                                    </div>

                                    <div>
                                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tingkat Kesulitan <span class="text-red-500">*</span>
                                        </label>
                                        <select id="difficulty" name="difficulty" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['difficulty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Pilih Kesulitan</option>
                                            <?php $__currentLoopData = $difficulties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $difficulty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($difficulty); ?>" <?php echo e(old('difficulty', $challenge->difficulty) == $difficulty ? 'selected' : ''); ?>>
                                                    <?php echo e($difficulty); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['difficulty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Points -->
                                <div class="mb-6">
                                    <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                                        Total Poin <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="points" name="points" value="<?php echo e(old('points', $challenge->points)); ?>" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="100" required>
                                    <?php $__errorArgs = ['points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <p class="mt-1 text-xs text-gray-500">Poin total untuk challenge ini (akan dibagi ke tasks)</p>
                                </div>

                                <!-- External Link -->
                                <div class="mb-6">
                                    <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2">
                                        Link Eksternal <span class="text-gray-400">(Opsional)</span>
                                    </label>
                                    <input type="url" id="external_link" name="external_link" value="<?php echo e(old('external_link', $challenge->external_link)); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['external_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="https://example.com/lab">
                                    <?php $__errorArgs = ['external_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <p class="mt-1 text-xs text-gray-500">Link ke lab hands-on atau resource eksternal</p>
                                </div>

                                <!-- Status -->
                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select id="status" name="status" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="draft" <?php echo e(old('status', $challenge->status) == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                        <option value="active" <?php echo e(old('status', $challenge->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="inactive" <?php echo e(old('status', $challenge->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <p class="mt-1 text-xs text-gray-500">Draft = belum dipublish, Active = bisa diakses user, Inactive = tidak bisa diakses</p>
                                </div>

                                <!-- Scheduling -->
                                <div class="mb-6" x-data="{ showScheduling: <?php echo e(old('scheduled_at', $challenge->scheduled_at) || old('available_at', $challenge->available_at) ? 'true' : 'false'); ?> }">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Penjadwalan Challenge <span class="text-gray-400">(Opsional)</span>
                                        </label>
                                        <button type="button" @click="showScheduling = !showScheduling"
                                                class="px-3 py-1 text-sm rounded-lg transition-colors"
                                                :class="showScheduling ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-blue-100 text-blue-700 hover:bg-blue-200'">
                                            <i class="fas" :class="showScheduling ? 'fa-times' : 'fa-calendar-alt'"></i>
                                            <span x-text="showScheduling ? 'Tutup' : 'Atur Jadwal'"></span>
                                        </button>
                                    </div>

                                    <div x-show="showScheduling" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div>
                                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-calendar-alt mr-1 text-blue-600"></i>Mulai Tersedia
                                            </label>
                                            <input type="datetime-local" id="scheduled_at" name="scheduled_at" 
                                                   value="<?php echo e(old('scheduled_at', $challenge->scheduled_at ? $challenge->scheduled_at->format('Y-m-d\TH:i') : '')); ?>"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <p class="mt-1 text-xs text-gray-600">Challenge akan terkunci sampai waktu ini</p>
                                        </div>

                                        <div>
                                            <label for="available_at" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-calendar-times mr-1 text-red-600"></i>Berakhir Pada
                                            </label>
                                            <input type="datetime-local" id="available_at" name="available_at" 
                                                   value="<?php echo e(old('available_at', $challenge->available_at ? $challenge->available_at->format('Y-m-d\TH:i') : '')); ?>"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['available_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <?php $__errorArgs = ['available_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <p class="mt-1 text-xs text-gray-600">Challenge tidak dapat diakses setelah waktu ini</p>
                                        </div>

                                        <div class="md:col-span-2 bg-yellow-50 border border-yellow-200 rounded p-3">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-info-circle text-yellow-400"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-yellow-800">Tips Penjadwalan</h3>
                                                    <div class="mt-2 text-sm text-yellow-700">
                                                        <ul class="list-disc list-inside space-y-1">
                                                            <li>Jika status <strong>Active</strong> tapi belum waktunya, challenge akan terkunci</li>
                                                            <li>Jika status <strong>Draft</strong>, challenge tidak akan terlihat sama sekali</li>
                                                            <li>Kosongkan kedua field untuk challenge yang selalu tersedia</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                                    <a href="<?php echo e(route('admin.challenges.index')); ?>" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                        Batal
                                    </a>
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-save mr-2"></i>Update Challenge
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Challenge Stats -->
                    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Challenge</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600"><?php echo e($challenge->tasks->count()); ?></div>
                                <div class="text-sm text-gray-500">Total Tasks</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600"><?php echo e($challenge->submissions()->where('status', 'correct')->count()); ?></div>
                                <div class="text-sm text-gray-500">Submissions Benar</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-600"><?php echo e($challenge->submissions()->distinct('user_id')->count()); ?></div>
                                <div class="text-sm text-gray-500">Peserta Unik</div>
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

        // Validation errors are handled by separate script tags

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
    
    <?php if($errors->any()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Error!',
                html: '<?php echo implode("<br>", $errors->all()); ?>',
                confirmButtonText: 'OK'
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\cyber-infinity-web\resources\views/admin/challenges/edit.blade.php ENDPATH**/ ?>