<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($ctf->name); ?> - Cyber Infinity</title>
    
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
    
    <!-- Custom CSS for Modal -->
    <style>
        .modal-overlay {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.7);
        }
        
        .challenge-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .challenge-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        
        .solved-challenge {
            background: linear-gradient(135deg, #10b981, #059669);
            border: 2px solid #34d399;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .solved-challenge .challenge-content {
            background: rgba(255, 255, 255, 0.95);
        }
        
        .challenge-modal {
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .challenge-modal::-webkit-scrollbar {
            width: 8px;
        }
        
        .challenge-modal::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .challenge-modal::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .challenge-modal::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .challenge-category-badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .challenge-points-badge {
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
        }
        
        .challenge-difficulty-badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .flag-input {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .flag-input:focus {
            background: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
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
                            <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.challenges.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola Challenges
                            </a>
                            <?php endif; ?>
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
                            <a href="<?php echo e(route('ctf.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-flag w-4 h-4 mr-3"></i>
                                CTF Events
                            </a>
                            <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.ctf.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                Kelola CTF
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if(auth()->user()->isAdmin()): ?>
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
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(ucfirst(auth()->user()->role)); ?></p>
                            <div class="text-xs font-medium space-y-1">
                                <p class="text-blue-600">Lab: <?php echo e(auth()->user()->points ?? 0); ?> pts</p>
                                <p class="text-purple-600">CTF: <?php echo e(auth()->user()->ctf_points ?? 0); ?> pts</p>
                            </div>
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
                        <div class="flex items-center">
                            <a href="<?php echo e(route('ctf.index')); ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900"><?php echo e($ctf->name); ?></h1>
                                <div class="flex items-center gap-2 mt-1">
                                    <?php if($ctf->isActive()): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full animate-pulse">
                                            <i class="fas fa-circle mr-1"></i>LIVE
                                        </span>
                                    <?php elseif($ctf->hasEnded()): ?>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full">
                                            <i class="fas fa-flag-checkered mr-1"></i>SELESAI
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                            <i class="fas fa-clock mr-1"></i>UPCOMING
                                        </span>
                                    <?php endif; ?>
                                    <span class="text-sm text-gray-500">
                                        <?php echo e($ctf->start_time->format('d M Y H:i')); ?> - <?php echo e($ctf->end_time->format('d M Y H:i')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo e(route('ctf.index')); ?>" 
                               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <a href="<?php echo e(route('ctf.submissions', $ctf)); ?>" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                <i class="fas fa-paper-plane mr-2"></i>Submissions
                            </a>
                            <a href="<?php echo e(route('ctf.leaderboard', $ctf)); ?>" 
                               class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                                <i class="fas fa-trophy mr-2"></i>Leaderboard
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CTF Content -->
            <main class="flex-1 p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Challenges -->
                        <div class="flex-1">
                            <!-- CTF Description -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi CTF</h2>
                                    <p class="text-gray-700 leading-relaxed"><?php echo e($ctf->description); ?></p>
                                </div>
                            </div>

                            <!-- User Progress -->
                            <?php if(auth()->guard()->check()): ?>
                                <?php if($userStats): ?>
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h2 class="text-lg font-semibold text-gray-900">Progress Anda</h2>
                                                <?php if($userStats['solved'] > 0): ?>
                                                    <div class="flex items-center text-green-600">
                                                        <i class="fas fa-check-circle mr-2"></i>
                                                        <span class="font-medium"><?php echo e($userStats['solved']); ?> soal selesai</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="grid grid-cols-3 gap-4 text-center">
                                                <div>
                                                    <div class="text-2xl font-bold text-blue-600"><?php echo e($userStats['points']); ?></div>
                                                    <div class="text-xs text-gray-500">Poin</div>
                                                </div>
                                                <div>
                                                    <div class="text-2xl font-bold text-green-600"><?php echo e($userStats['solved']); ?></div>
                                                    <div class="text-xs text-gray-500">Solved</div>
                                                </div>
                                                <div>
                                                    <div class="text-2xl font-bold text-yellow-600">
                                                        <?php echo e($userStats['rank'] ? '#' . $userStats['rank'] : '-'); ?>

                                                    </div>
                                                    <div class="text-xs text-gray-500">Rank</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Challenges by Category -->
                            <?php if($challenges->count() > 0): ?>
                                <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categorysChallenges): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-8">
                                        <div class="flex items-center mb-4">
                                            <h2 class="text-xl font-bold text-gray-900 mr-4"><?php echo e($category); ?></h2>
                                            <div class="flex-1 h-px bg-gray-200"></div>
                                            <span class="ml-4 text-sm text-gray-500"><?php echo e($categorysChallenges->count()); ?> soal</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                            <?php $__currentLoopData = $categorysChallenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $isSolved = in_array($challenge->id, $userSubmissions);
                                                    $userHintsForChallenge = $userHints[$challenge->id] ?? [];
                                                ?>
                                                
                                                <div class="challenge-card bg-white rounded-lg border-2 <?php echo e($isSolved ? 'solved-challenge' : 'border-gray-200 hover:border-blue-300'); ?> cursor-pointer overflow-hidden"
                                                     onclick="openChallengeModal(<?php echo e($challenge->id); ?>)">
                                                    <?php if($isSolved): ?>
                                                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 text-center">
                                                            <i class="fas fa-trophy mr-2"></i>
                                                            <span class="font-bold">SOLVED</span>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="challenge-content p-4">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <h3 class="text-lg font-bold text-gray-900 truncate"><?php echo e($challenge->title); ?></h3>
                                                            <?php if($isSolved): ?>
                                                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="flex items-center justify-between mb-3">
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($challenge->getCategoryColor()); ?>">
                                                                <?php echo e($challenge->category); ?>

                                                            </span>
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($challenge->getPointsColor()); ?>">
                                                                <?php echo e($challenge->points); ?> pts
                                                            </span>
                                                        </div>
                                                        
                                                        <p class="text-gray-600 text-sm line-clamp-2 mb-3"><?php echo e(Str::limit($challenge->description, 100)); ?></p>
                                                        
                                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                                            <span>
                                                                <i class="fas fa-users mr-1"></i>
                                                                <?php echo e($challenge->solve_count); ?> solves
                                                            </span>
                                                            <?php if($challenge->hints && count($challenge->hints) > 0): ?>
                                                                <span>
                                                                    <i class="fas fa-lightbulb mr-1"></i>
                                                                    <?php echo e(count($challenge->hints)); ?> hints
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <?php if($challenge->solve_count > 0): ?>
                                                            <?php
                                                                $firstSolver = $challenge->getFirstSolver();
                                                                $recentSolvers = $challenge->getSolvers(3);
                                                            ?>
                                                            <div class="text-xs">
                                                                <?php if($firstSolver): ?>
                                                                    <div class="flex items-center text-yellow-600 mb-1">
                                                                        <i class="fas fa-crown text-xs mr-1"></i>
                                                                        <span class="font-medium"><?php echo e($firstSolver->username ?? $firstSolver->name); ?></span>
                                                                        <span class="text-gray-400 ml-1">(first blood)</span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if($recentSolvers->count() > 1): ?>
                                                                    <div class="text-gray-500">
                                                                        <?php $__currentLoopData = $recentSolvers->skip(1)->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <span class="mr-2"><?php echo e($solver['user']->username ?? $solver['user']->name); ?></span>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($challenge->solve_count > 3): ?>
                                                                            <span class="text-gray-400">+<?php echo e($challenge->solve_count - 3); ?> more</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-puzzle-piece text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Belum Ada Soal</h3>
                                    <p class="text-gray-600">Soal akan tersedia ketika CTF dimulai.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Leaderboard Sidebar -->
                        <div class="lg:w-80">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                                        Leaderboard
                                    </h3>
                                    <a href="<?php echo e(route('ctf.leaderboard', $ctf)); ?>" class="text-blue-600 hover:text-blue-700 text-sm">
                                        Lihat Semua
                                    </a>
                                </div>
                                
                                <?php if($leaderboard->count() > 0): ?>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors duration-200"
                                                 onclick="window.location='/ctf/<?php echo e($ctf->id); ?>/user/<?php echo e($user->id); ?>'">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 <?php echo e($index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-orange-500' : 'bg-gray-300'))); ?>">
                                                        <?php if($index < 3): ?>
                                                            <i class="fas fa-trophy text-white text-sm"></i>
                                                        <?php else: ?>
                                                            <span class="text-gray-600 text-sm font-bold"><?php echo e($index + 1); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-900 font-medium"><?php echo e($user->name); ?></div>
                                                        <div class="text-gray-500 text-xs"><?php echo e($user->solved_challenges); ?> solves</div>
                                                    </div>
                                                </div>
                                                <div class="text-blue-600 font-bold"><?php echo e($user->total_points); ?></div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-8">
                                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500">Belum ada peserta</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Challenge Modal -->
    <div id="challengeModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay absolute inset-0" onclick="closeChallengeModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="challenge-modal bg-white w-full shadow-2xl">
                <div id="challengeModalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        const challenges = <?php echo json_encode($challenges->flatten(), 15, 512) ?>;
        const userSubmissions = <?php echo json_encode($userSubmissions, 15, 512) ?>;
        const userHints = <?php echo json_encode($userHints, 15, 512) ?>;
        const ctfId = <?php echo e($ctf->id); ?>;

        function openChallengeModal(challengeId) {
            const challenge = challenges.find(c => c.id === challengeId);
            if (!challenge) return;

            const isSolved = userSubmissions.includes(challengeId);
            const userHintsForChallenge = userHints[challengeId] || [];

            let modalContent = `
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">${challenge.title}</h2>
                            <div class="flex items-center space-x-3">
                                <span class="challenge-category-badge ${getCategoryColorClass(challenge.category)}">
                                    ${challenge.category}
                                </span>
                                <span class="challenge-difficulty-badge ${getDifficultyColorClass(challenge.difficulty)}">
                                    <i class="${getDifficultyIconClass(challenge.difficulty)} mr-1"></i>
                                    ${challenge.difficulty}
                                </span>
                                <span class="challenge-points-badge ${getPointsColorClass(challenge.points)}">
                                    ${challenge.points} pts
                                </span>
                                ${isSolved ? '<span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full"><i class="fas fa-check mr-1"></i>Solved</span>' : ''}
                            </div>
                        </div>
                        <button onclick="closeChallengeModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">
                            <i class="fas fa-file-text text-blue-500 mr-2"></i>
                            Deskripsi
                        </h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                                ${challenge.description.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    </div>
                    
                    ${challenge.files && challenge.files.length > 0 ? `
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">File</h3>
                            <div class="space-y-2">
                                ${challenge.files.map((file, index) => `
                                    <a href="/ctf/challenges/${challenge.id}/files/${index}/download" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-download mr-2"></i>
                                        ${file.name}
                                        <span class="ml-2 text-xs opacity-75">(${formatFileSize(file.size)})</span>
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                    
                    ${challenge.hints && challenge.hints.length > 0 ? `
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                Hints
                            </h3>
                            <div class="space-y-3">
                                ${challenge.hints.map((hint, index) => {
                                    const isPurchased = userHintsForChallenge.includes(index);
                                    return `
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-gray-900 font-medium">${hint.title}</h4>
                                                ${!isPurchased ? `
                                                    <button onclick="purchaseHint(${challengeId}, ${index})" class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-lg transition-colors duration-200">
                                                        <i class="fas fa-coins mr-1"></i>
                                                        ${hint.cost} CTF Points
                                                    </button>
                                                ` : '<span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-lg"><i class="fas fa-check mr-1"></i>Dibeli</span>'}
                                            </div>
                                            ${isPurchased ? `<p class="text-gray-700">${hint.content}</p>` : '<p class="text-gray-500 italic">Beli hint ini untuk melihat konten</p>'}
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    ` : ''}
                    
                    ${!isSolved && <?php echo e($ctf->isActive() ? 'true' : 'false'); ?> ? `
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">
                                <i class="fas fa-flag text-green-500 mr-2"></i>
                                Submit Flag
                            </h3>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <form onsubmit="submitFlag(event, ${challengeId})" class="space-y-3">
                                    <div>
                                        <label for="flagInput" class="block text-sm font-medium text-gray-700 mb-2">Flag:</label>
                                        <input type="text" id="flagInput" placeholder="flag{enter_your_flag_here}" 
                                               class="flag-input w-full px-4 py-3 rounded-lg focus:outline-none text-sm" required>
                                    </div>
                                    <button type="submit" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-paper-plane mr-2"></i>Submit Flag
                                    </button>
                                </form>
                            </div>
                        </div>
                    ` : (isSolved ? `
                        <div class="mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                <h3 class="text-lg font-bold text-green-800 mb-1">Challenge Solved!</h3>
                                <p class="text-green-600">You have successfully solved this challenge.</p>
                            </div>
                        </div>
                    ` : `
                        <div class="mb-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <i class="fas fa-clock text-yellow-500 text-2xl mb-2"></i>
                                <h3 class="text-lg font-bold text-yellow-800 mb-1">CTF Not Active</h3>
                                <p class="text-yellow-600">This CTF is not currently active for submissions.</p>
                            </div>
                        </div>
                    `)}
                    
                    <!-- Solvers Section -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                Solvers (${challenge.solve_count})
                            </h4>
                            ${challenge.solve_count > 0 ? `
                                <button onclick="loadSolvers(${challenge.id})" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    <i class="fas fa-refresh mr-1"></i>Refresh
                                </button>
                            ` : ''}
                        </div>
                        
                        <div id="solvers-container-${challenge.id}" class="space-y-2">
                            ${challenge.solve_count > 0 ? `
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                    <p class="text-sm text-gray-500 mt-2">Loading solvers...</p>
                                </div>
                            ` : `
                                <div class="text-center py-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-ghost text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">No solvers yet</p>
                                    <p class="text-xs text-gray-400">Be the first to solve this challenge!</p>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('challengeModalContent').innerHTML = modalContent;
            document.getElementById('challengeModal').classList.remove('hidden');
            
            // Load solvers if challenge has been solved
            if (challenge.solve_count > 0) {
                loadSolvers(challenge.id);
            }
        }

        function closeChallengeModal() {
            document.getElementById('challengeModal').classList.add('hidden');
        }

        function loadSolvers(challengeId) {
            const container = document.getElementById(`solvers-container-${challengeId}`);
            if (!container) return;

            // Show loading state
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-gray-400"></i>
                    <p class="text-sm text-gray-500 mt-2">Loading solvers...</p>
                </div>
            `;

            fetch(`/ctf/challenges/${challengeId}/solvers`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.solvers.length > 0) {
                        let solversHtml = '';
                        
                        data.solvers.forEach((solver, index) => {
                            const isFirstBlood = index === 0;
                            const solvedTime = new Date(solver.solved_at).toLocaleString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            
                            const profileUrl = `<?php echo e(url('/ctf/' . $ctf->id . '/user')); ?>/${solver.user.id}`;
                            solversHtml += `
                                <div class="flex items-center justify-between p-3 ${isFirstBlood ? 'bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200' : 'bg-gray-50'} rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                                     onclick="window.location='${profileUrl}'"
                                     title="Lihat profil ${solver.user.name}">
                                    <div class="flex items-center">
                                        ${isFirstBlood ? `
                                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-3 shadow-lg">
                                                <i class="fas fa-crown text-white text-sm"></i>
                                            </div>
                                        ` : `
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-gray-600 text-sm font-bold">${index + 1}</span>
                                            </div>
                                        `}
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                                ${solver.user.username || solver.user.name}
                                                ${isFirstBlood ? '<i class="fas fa-trophy text-yellow-500 ml-2" title="First Blood!"></i>' : ''}
                                            </div>
                                            <div class="text-xs text-gray-500">${solvedTime}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-green-600">+${solver.points_earned}</div>
                                        ${isFirstBlood ? '<div class="text-xs text-yellow-600 font-medium">First Blood</div>' : ''}
                                    </div>
                                </div>
                            `;
                        });
                        
                        container.innerHTML = solversHtml;
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-ghost text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">No solvers yet</p>
                                <p class="text-xs text-gray-400">Be the first to solve this challenge!</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading solvers:', error);
                    container.innerHTML = `
                        <div class="text-center py-4 bg-red-50 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-400 text-lg mb-2"></i>
                            <p class="text-sm text-red-600">Error loading solvers</p>
                            <button onclick="loadSolvers(${challengeId})" class="text-xs text-red-500 hover:text-red-700 mt-1">
                                Try again
                            </button>
                        </div>
                    `;
                });
        }

        function submitFlag(event, challengeId) {
            event.preventDefault();
            const flag = document.getElementById('flagInput').value;
            
            if (!flag.trim()) return;

            // Show loading
            Swal.fire({
                title: 'Mengirim...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/ctf/${ctfId}/challenges/${challengeId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ flag: flag })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Benar!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Salah!',
                        text: data.message,
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            });
        }

        function purchaseHint(challengeId, hintIndex) {
            Swal.fire({
                title: 'Beli Hint?',
                text: 'Apakah Anda yakin ingin membeli hint ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Beli',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/ctf/challenges/${challengeId}/hints/purchase`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ hint_index: hintIndex })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan. Silakan coba lagi.',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        }

        function getCategoryColorClass(category) {
            const colors = {
                'Web': 'bg-blue-100 text-blue-800',
                'Crypto': 'bg-purple-100 text-purple-800',
                'Forensic': 'bg-green-100 text-green-800',
                'OSINT': 'bg-yellow-100 text-yellow-800',
                'Reverse': 'bg-red-100 text-red-800',
                'Pwn': 'bg-gray-100 text-gray-800',
                'Linux': 'bg-indigo-100 text-indigo-800',
                'Network': 'bg-teal-100 text-teal-800',
                'Mobile': 'bg-pink-100 text-pink-800',
                'Hardware': 'bg-orange-100 text-orange-800'
            };
            return colors[category] || 'bg-gray-100 text-gray-800';
        }

        function getPointsColorClass(points) {
            if (points <= 100) return 'bg-green-100 text-green-800';
            if (points <= 300) return 'bg-yellow-100 text-yellow-800';
            if (points <= 500) return 'bg-orange-100 text-orange-800';
            return 'bg-red-100 text-red-800';
        }

        function getDifficultyColorClass(difficulty) {
            const colors = {
                'Easy': 'bg-green-100 text-green-800 border-green-200',
                'Medium': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'Hard': 'bg-red-100 text-red-800 border-red-200'
            };
            return colors[difficulty] || 'bg-gray-100 text-gray-800 border-gray-200';
        }

        function getDifficultyIconClass(difficulty) {
            const icons = {
                'Easy': 'fas fa-circle text-green-500',
                'Medium': 'fas fa-circle-half-stroke text-yellow-500',
                'Hard': 'fas fa-circle text-red-500'
            };
            return icons[difficulty] || 'fas fa-circle text-gray-500';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeChallengeModal();
            }
        });

        // Show success/error messages
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo e(session("success")); ?>',
                showConfirmButton: false,
                timer: 3000
            });
        <?php endif; ?>

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
</html><?php /**PATH C:\laragon\www\cyber-infinity\resources\views/ctf/show.blade.php ENDPATH**/ ?>