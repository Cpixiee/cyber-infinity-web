<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $challenge->title }} - Locked</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <!-- Main Content -->
    <main class="min-h-screen pt-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Locked Challenge Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header with Lock Icon -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-8 text-center">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-4xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Challenge Terkunci</h1>
                    <p class="text-yellow-100">Challenge ini belum dapat diakses</p>
                </div>

                <!-- Challenge Info -->
                <div class="p-6">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $challenge->title }}</h2>
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-1 text-blue-500"></i>
                                <span>{{ $challenge->category }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-signal mr-1 text-green-500"></i>
                                <span>{{ $challenge->difficulty }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-star mr-1 text-yellow-500"></i>
                                <span>{{ $challenge->points }} poin</span>
                            </div>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    @if($challenge->scheduled_at)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                                    <i class="fas fa-clock mr-2"></i>Challenge Akan Tersedia Dalam:
                                </h3>
                                <div id="countdown" class="text-3xl font-bold text-yellow-600 mb-2"></div>
                                <p class="text-sm text-yellow-700">
                                    Mulai: {{ $challenge->scheduled_at->format('d M Y, H:i') }} WIB
                                </p>
                                @if($challenge->available_at)
                                    <p class="text-sm text-yellow-700">
                                        Berakhir: {{ $challenge->available_at->format('d M Y, H:i') }} WIB
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Challenge Description Preview -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">
                            <i class="fas fa-eye mr-2 text-gray-600"></i>Preview Deskripsi
                        </h4>
                        <div class="text-gray-700 leading-relaxed">
                            {{ Str::limit(strip_tags($challenge->description), 200) }}
                            @if(strlen(strip_tags($challenge->description)) > 200)
                                <span class="text-gray-500">...</span>
                            @endif
                        </div>
                    </div>

                    <!-- Tasks Preview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h4 class="font-semibold text-blue-900 mb-3">
                            <i class="fas fa-tasks mr-2 text-blue-600"></i>Yang Akan Kamu Kerjakan
                        </h4>
                        <div class="space-y-2">
                            @foreach($challenge->tasks->take(3) as $index => $task)
                                <div class="flex items-center text-blue-800">
                                    <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium">{{ $index + 1 }}</span>
                                    </div>
                                    <span class="text-sm">{{ $task->title }}</span>
                                    <span class="ml-auto text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded">
                                        {{ $task->points }} pts
                                    </span>
                                </div>
                            @endforeach
                            @if($challenge->tasks->count() > 3)
                                <div class="flex items-center text-blue-600 text-sm">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-plus text-xs"></i>
                                    </div>
                                    <span>{{ $challenge->tasks->count() - 3 }} task lainnya...</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-center space-x-4">
                        <a href="{{ route('challenges.index') }}" 
                           class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Challenges
                        </a>
                        <button onclick="location.reload()" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-refresh mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @if($challenge->scheduled_at)
        <script>
            // Countdown Timer
            function updateCountdown() {
                const targetDate = new Date('{{ $challenge->scheduled_at->format("Y-m-d H:i:s") }}').getTime();
                const now = new Date().getTime();
                const difference = targetDate - now;

                if (difference > 0) {
                    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                    let countdownText = '';
                    if (days > 0) countdownText += days + 'd ';
                    if (hours > 0) countdownText += hours + 'h ';
                    if (minutes > 0) countdownText += minutes + 'm ';
                    countdownText += seconds + 's';

                    document.getElementById('countdown').textContent = countdownText;
                } else {
                    document.getElementById('countdown').textContent = 'Challenge Tersedia!';
                    // Auto refresh after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }

            // Update countdown every second
            updateCountdown();
            setInterval(updateCountdown, 1000);
        </script>
    @endif
</body>
</html>


