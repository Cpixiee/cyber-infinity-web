@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('ctf.show', $ctf) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Leaderboard - {{ $ctf->name }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            @if($ctf->isActive())
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full animate-pulse">
                                    <i class="fas fa-circle mr-1"></i>LIVE
                                </span>
                            @elseif($ctf->hasEnded())
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full">
                                    <i class="fas fa-flag-checkered mr-1"></i>FINAL RESULTS
                                </span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                    <i class="fas fa-clock mr-1"></i>UPCOMING
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">
                                {{ $ctf->start_time->format('d M Y') }} - {{ $ctf->end_time->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('ctf.show', $ctf) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-flag mr-2"></i>Lihat Soal
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Participants</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalParticipants) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Challenges</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalChallenges) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-puzzle-piece text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Solves</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalSolves) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($chartData) > 0)
            <!-- Chart Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Points Progression</h2>
                        <p class="text-gray-500 text-sm mt-1">Top 10 players' points over time for this CTF</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        <span class="text-sm text-gray-500">Live Updates</span>
                    </div>
                </div>
                
                <div style="position: relative; height: 400px; width: 100%;">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
            @endif

            <!-- Leaderboard -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $ctf->name }} Leaderboard</h2>
                            <p class="text-gray-500 text-sm mt-1">
                                @if($ctf->isActive())
                                    Live rankings - updates in real-time
                                @else
                                    Final rankings for this CTF event
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-trophy text-yellow-500"></i>
                            <span class="text-sm text-gray-500">Top {{ count($leaderboard) }} Players</span>
                        </div>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($leaderboard as $index => $userData)
                        @php
                            $user = $userData['user'];
                            $points = $userData['points'];
                            $solves = $userData['solves'];
                            $lastSubmit = $userData['last_submit'];
                        @endphp
                        <div class="p-6 flex items-center justify-between hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                             onclick="window.location.href='{{ route('ctf.user.profile', [$ctf, $user]) }}'">
                            <div class="flex items-center space-x-4">
                                <!-- Rank Badge -->
                                <div class="flex-shrink-0">
                                    @if($index == 0)
                                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-crown text-white text-lg"></i>
                                        </div>
                                    @elseif($index == 1)
                                        <div class="w-12 h-12 bg-gradient-to-r from-gray-300 to-gray-400 rounded-full flex items-center justify-center shadow-lg">
                                            <span class="text-white font-bold text-lg">2</span>
                                        </div>
                                    @elseif($index == 2)
                                        <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                                            <span class="text-white font-bold text-lg">3</span>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center border-2 border-gray-200">
                                            <span class="text-gray-600 font-bold text-lg">{{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- User Info -->
                                <div class="flex items-center space-x-3">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center border-2 border-gray-200">
                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500">@{{ $user->username }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="flex items-center space-x-8 text-right">
                                <div>
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($points) }}</p>
                                    <p class="text-sm text-gray-500">Points</p>
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-green-600">{{ number_format($solves) }}</p>
                                    <p class="text-sm text-gray-500">Solves</p>
                                </div>
                                @if($lastSubmit)
                                <div>
                                    <p class="text-sm text-gray-600">{{ $lastSubmit->diffForHumans() }}</p>
                                    <p class="text-xs text-gray-500">Last Submit</p>
                                </div>
                                @endif
                                <div class="text-gray-400">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Participants Yet</h3>
                            <p class="text-gray-500">Be the first to solve challenges and claim the top spot!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>

@if(count($chartData) > 0)
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    
    if (chartData && chartData.length > 0) {
        const ctx = document.getElementById('progressChart').getContext('2d');
        
        // Generate colors for each user
        const colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#06B6D4', '#F97316', '#84CC16', '#EC4899', '#6366F1'
        ];
        
        const datasets = chartData.map((user, index) => ({
            label: user.name,
            data: user.data.map(point => ({
                x: point.date,
                y: point.points
            })),
            borderColor: colors[index % colors.length],
            backgroundColor: colors[index % colors.length] + '20',
            borderWidth: 3,
            fill: false,
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: colors[index % colors.length],
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2
        }));

        new Chart(ctx, {
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
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'Time: ' + context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' points';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'hour',
                            displayFormats: {
                                hour: 'MMM dd HH:mm'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Time',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
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
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endif
@endsection