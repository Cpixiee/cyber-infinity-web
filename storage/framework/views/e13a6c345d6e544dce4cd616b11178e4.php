<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Leaderboard - <?php echo e($ctf->name); ?> | Cyber Infinity</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/fih-logo.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/fih-logo.png')); ?>">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        /* Custom animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .rank-badge {
            transition: all 0.3s ease;
        }
        
        .rank-item:hover .rank-badge {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="<?php echo e(route('ctf.show', $ctf)); ?>" class="mr-4 text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Leaderboard - <?php echo e($ctf->name); ?></h1>
                        <div class="flex items-center gap-3 mt-1">
                            <?php if($ctf->isActive()): ?>
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full animate-pulse">
                                    <i class="fas fa-circle mr-1 text-xs"></i>LIVE
                                </span>
                            <?php elseif($ctf->hasEnded()): ?>
                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full">
                                    <i class="fas fa-flag-checkered mr-1"></i>FINAL RESULTS
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
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
                    <a href="<?php echo e(route('ctf.show', $ctf)); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-flag mr-2"></i>Lihat Soal
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Participants</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format($totalParticipants)); ?></p>
                        <p class="text-xs text-gray-500 mt-1">Total registered users</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Challenges</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format($totalChallenges)); ?></p>
                        <p class="text-xs text-gray-500 mt-1">Available problems</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-puzzle-piece text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Solves</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format($totalSolves)); ?></p>
                        <p class="text-xs text-gray-500 mt-1">Successful submissions</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Points Progression</h2>
                        <p class="text-gray-500 text-sm mt-1">
                            <?php if(count($chartData) > 0): ?>
                                Top <?php echo e(count($chartData)); ?> players' points over time for this CTF
                            <?php else: ?>
                                Chart will appear when participants start solving challenges
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-gray-500">Live Updates</span>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <?php if(count($chartData) > 0): ?>
                    <div style="position: relative; height: 450px; width: 100%;">
                        <canvas id="progressChart"></canvas>
                    </div>
                <?php else: ?>
                    <div class="h-80 flex items-center justify-center border-2 border-dashed border-gray-200 rounded-lg">
                        <div class="text-center">
                            <div class="text-6xl text-gray-300 mb-4">📈</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Chart Data Yet</h3>
                            <p class="text-gray-500">Points progression will appear when users start solving challenges</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

            <!-- Leaderboard -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900"><?php echo e($ctf->name); ?> Leaderboard</h2>
                            <p class="text-gray-500 text-sm mt-1">
                                <?php if($ctf->isActive()): ?>
                                    Live rankings - updates in real-time
                                <?php else: ?>
                                    Final rankings for this CTF event
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-trophy text-yellow-500"></i>
                            <span class="text-sm text-gray-500">Top <?php echo e(count($leaderboard)); ?> Players</span>
                        </div>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 cursor-pointer transition-colors duration-200 rank-item"
                         onclick="window.location.href='/ctf/<?php echo e($ctf->id); ?>/user/<?php echo e($user->id); ?>'">
                            <div class="flex items-center space-x-4">
                                <!-- Rank Badge -->
                                <div class="flex-shrink-0">
                                    <?php if($index == 0): ?>
                                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center shadow-lg rank-badge">
                                            <i class="fas fa-crown text-white text-lg"></i>
                                        </div>
                                    <?php elseif($index == 1): ?>
                                    <div class="w-12 h-12 bg-gradient-to-r from-gray-300 to-gray-400 rounded-full flex items-center justify-center shadow-lg rank-badge">
                                            <span class="text-white font-bold text-lg">2</span>
                                        </div>
                                    <?php elseif($index == 2): ?>
                                    <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg rank-badge">
                                            <span class="text-white font-bold text-lg">3</span>
                                        </div>
                                    <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center border-2 border-gray-200 rank-badge">
                                            <span class="text-gray-600 font-bold text-lg"><?php echo e($index + 1); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- User Info -->
                                <div class="flex items-center space-x-3">
                                    <?php if($user->avatar): ?>
                                        <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center border-2 border-gray-200">
                                            <span class="text-white font-bold text-lg"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900"><?php echo e($user->name); ?></h3>
                                        <p class="text-sm text-gray-500">
                                            <?php if($user->username): ?>
                                                <?php echo e('@' . $user->username); ?>

                                            <?php else: ?>
                                                <?php echo e('@' . strtolower(str_replace(' ', '', $user->name))); ?>

                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="flex items-center space-x-8 text-right">
                                <div>
                                <p class="text-2xl font-bold text-blue-600"><?php echo e(number_format($user->total_points)); ?></p>
                                    <p class="text-sm text-gray-500">Points</p>
                                </div>
                                <div>
                                <p class="text-lg font-semibold text-green-600"><?php echo e(number_format($user->solved_challenges)); ?></p>
                                    <p class="text-sm text-gray-500">Solves</p>
                                </div>
                                <div class="text-gray-400">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Participants Yet</h3>
                            <p class="text-gray-500">Be the first to solve challenges and claim the top spot!</p>
                        </div>
                    <?php endif; ?>
            </div>
        </div>
    </main>

    <?php if(count($chartData) > 0): ?>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
        
        if (chartData && chartData.length > 0) {
        const ctx = document.getElementById('progressChart').getContext('2d');
        
            // Generate vibrant colors for each user
        const colors = [
                '#3B82F6', // Blue
                '#EF4444', // Red  
                '#10B981', // Green
                '#F59E0B', // Amber
                '#8B5CF6', // Purple
                '#06B6D4', // Cyan
                '#F97316', // Orange
                '#84CC16', // Lime
                '#EC4899', // Pink
                '#6366F1'  // Indigo
        ];
        
        const datasets = chartData.map((user, index) => ({
            label: user.name,
            data: user.data.map(point => ({
                x: point.date,
                y: point.points
            })),
            borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '15',
            borderWidth: 3,
            fill: false,
            tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
            pointBackgroundColor: colors[index % colors.length],
            pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: colors[index % colors.length],
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
        }));

            const chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                            align: 'start',
                        labels: {
                            usePointStyle: true,
                                pointStyle: 'circle',
                            padding: 20,
                            font: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                color: '#374151',
                                generateLabels: function(chart) {
                                    const original = Chart.defaults.plugins.legend.labels.generateLabels;
                                    const labels = original.call(this, chart);
                                    
                                    labels.forEach((label, i) => {
                                        label.fillStyle = colors[i % colors.length];
                                        label.strokeStyle = colors[i % colors.length];
                                    });
                                    
                                    return labels;
                                }
                        }
                    },
                    tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleColor: '#F9FAFB',
                            bodyColor: '#F9FAFB',
                            borderColor: 'rgba(156, 163, 175, 0.2)',
                        borderWidth: 1,
                            cornerRadius: 12,
                        displayColors: true,
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                title: function(context) {
                                    const date = new Date(context[0].parsed.x);
                                    return date.toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                            },
                            label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} points`;
                                }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'hour',
                                stepSize: 1,
                            displayFormats: {
                                    hour: 'HH:mm'
                            }
                        },
                        title: {
                            display: true,
                                text: 'Time (Hours)',
                            font: {
                                size: 14,
                                weight: 'bold'
                                },
                                color: '#374151'
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.2)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 12
                                },
                                maxTicksLimit: 8
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Points',
                            font: {
                                size: 14,
                                weight: 'bold'
                                },
                                color: '#374151'
                        },
                        grid: {
                                color: 'rgba(156, 163, 175, 0.2)',
                                drawBorder: false
                        },
                        ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 12
                                },
                            callback: function(value) {
                                return value.toLocaleString();
                                }
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderJoinStyle: 'round',
                            borderCapStyle: 'round'
                    }
                }
            }
        });
            }
    });
    </script>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\laragon\www\cyber-infinity\resources\views/ctf/leaderboard.blade.php ENDPATH**/ ?>