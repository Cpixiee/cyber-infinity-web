<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $challenge->title }} - Cyber Infinity</title>
    
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
    
    <!-- Custom CSS for Video Player -->
    <style>
        .video-container video {
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .video-container video:focus {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }
        
        /* Custom video controls styling */
        .video-container video::-webkit-media-controls-panel {
            background-color: rgba(0, 0, 0, 0.8);
        }
        
        .video-container video::-webkit-media-controls-play-button,
        .video-container video::-webkit-media-controls-volume-slider {
            filter: brightness(1.2);
        }
    </style>
    
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
                    <div x-data="{ open: true }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
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
                    
                    @if(auth()->user()->isAdmin())
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
                            <a href="{{ route('challenges.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $challenge->title }}</h1>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $challenge->getCategoryColor() }}">
                                        {{ $challenge->category }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $challenge->getDifficultyColor() }}">
                                        {{ $challenge->difficulty }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>{{ $challenge->points }} poin
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if($challenge->external_link)
                                <a href="{{ $challenge->external_link }}" target="_blank" 
                                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                    <i class="fas fa-external-link-alt mr-2"></i>Lab Environment
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Challenge Content -->
            <main class="flex-1 p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Challenge Description -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Challenge</h2>
                            <p class="text-gray-700 leading-relaxed">{{ $challenge->description }}</p>
                        </div>
                    </div>

                    <!-- Progress Overview -->
                    @auth
                        @php
                            $completionPercentage = $challenge->getCompletionPercentage(auth()->user());
                            $totalTasks = $challenge->tasks->count();
                            $completedTasks = $userProgress->where('status', 'correct')->count();
                            $isCompleted = $challenge->isCompletedByUser(auth()->user());
                        @endphp
                        
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-lg font-semibold text-gray-900">Progress Anda</h2>
                                    @if($isCompleted)
                                        <div class="flex items-center text-green-600">
                                            <i class="fas fa-trophy mr-2"></i>
                                            <span class="font-medium">Challenge Selesai!</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>{{ $completedTasks }} dari {{ $totalTasks }} tasks selesai</span>
                                    <span>{{ $completionPercentage }}%</span>
                                </div>
                                
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                                </div>
                                
                                @if($userProgress->count() > 0)
                                    <div class="mt-4 text-sm text-gray-600">
                                        <span>Poin yang diperoleh: </span>
                                        <span class="font-medium text-blue-600">{{ $userProgress->where('status', 'correct')->sum('points_earned') }} poin</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endauth

                    <!-- Tasks -->
                    <div class="space-y-6">
                        @foreach($challenge->tasks as $index => $task)
                            @php
                                $userSubmission = null;
                                $canAccess = true;
                                $isCompleted = false;
                                $attempts = 0;
                                
                                if (auth()->check()) {
                                    $userSubmission = $task->getUserSubmission(auth()->user());
                                    $canAccess = $task->canUserAccess(auth()->user());
                                    $isCompleted = $task->isCompletedByUser(auth()->user());
                                    $attempts = $task->getUserAttemptsCount(auth()->user());
                                }
                            @endphp
                            
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 {{ !$canAccess ? 'opacity-50' : '' }}">
                                <div class="p-6">
                                    <!-- Task Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-start">
                                            <div class="w-8 h-8 {{ $isCompleted ? 'bg-green-100 text-green-600' : ($canAccess ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400') }} rounded-full flex items-center justify-center mr-3 mt-1">
                                                @if($isCompleted)
                                                    <i class="fas fa-check text-sm"></i>
                                                @else
                                                    <span class="text-sm font-medium">{{ $index + 1 }}</span>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $task->title }}</h3>
                                                @if($canAccess)
                                                    <p class="text-gray-700 leading-relaxed mb-3">{{ $task->description }}</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @if($task->external_link)
                                                            <a href="{{ $task->external_link }}" target="_blank" 
                                                               class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors duration-200">
                                                                <i class="fas fa-external-link-alt mr-2"></i>
                                                                Akses Lab/Resource
                                                            </a>
                                                        @endif
                                                        @if($task->hasFile())
                                                            <a href="{{ Storage::url($task->file_path) }}" target="_blank" 
                                                               class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors duration-200">
                                                                <i class="fas fa-download mr-2"></i>
                                                                Download {{ $task->file_name }}
                                                                <span class="ml-2 text-xs opacity-75">({{ $task->formatted_file_size }})</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <p class="text-gray-500 italic">Selesaikan task sebelumnya untuk membuka task ini</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if($canAccess && $task->points > 0)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i>{{ $task->points }} poin
                                                </span>
                                            @endif
                                            @if(!$canAccess)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                                    <i class="fas fa-lock mr-1"></i>Terkunci
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Task Status -->
                                    @if($userSubmission && $canAccess)
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between text-sm">
                                                <div class="flex items-center">
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $userSubmission->getStatusColor() }}">
                                                        <i class="{{ $userSubmission->getStatusIcon() }} mr-1"></i>
                                                        {{ ucfirst($userSubmission->status) }}
                                                    </span>
                                                    @if($attempts > 1)
                                                        <span class="ml-2 text-gray-500">{{ $attempts }} percobaan</span>
                                                    @endif
                                                </div>
                                                @if($userSubmission->status === 'correct')
                                                    <span class="text-green-600 font-medium">+{{ $userSubmission->points_earned }} poin</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Flag Submission Form -->
                                    @if($canAccess && !$isCompleted)
                                        <form action="{{ route('challenges.submit', [$challenge, $task]) }}" method="POST" class="mt-4">
                                            @csrf
                                            <div class="flex gap-3">
                                                <div class="flex-1">
                                                    <input type="text" name="flag" 
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                           placeholder="Masukkan flag (CYBER{...})" 
                                                           autocomplete="off" required>
                                                </div>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-paper-plane mr-2"></i>Submit
                                                </button>
                                            </div>
                                        </form>
                                    @elseif($isCompleted)
                                        <div class="mt-4 p-3 bg-green-50 rounded-lg">
                                            <div class="flex items-center text-green-700">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="font-medium">Task selesai! Flag: </span>
                                                <code class="ml-2 px-2 py-1 bg-green-100 rounded text-sm">{{ $task->flag }}</code>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hints Section -->
                                    @if($canAccess && $task->hints->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-gray-200" x-data="{ showHints: false }">
                                            <button @click="showHints = !showHints" 
                                                    class="w-full flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                                                <div class="flex items-center">
                                                    <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                                                    <span class="font-medium text-yellow-800">Hints Tersedia ({{ $task->hints->count() }})</span>
                                                </div>
                                                <i class="fas fa-chevron-down text-yellow-600 transition-transform" :class="{ 'rotate-180': showHints }"></i>
                                            </button>
                                            
                                            <div x-show="showHints" x-transition class="mt-3 space-y-3">
                                                @foreach($task->hints->where('is_active', true)->sortBy('order') as $hint)
                                                    <div class="border border-yellow-200 rounded-lg bg-white shadow-sm">
                                                        <div class="p-4">
                                                            <div class="flex items-start justify-between">
                                                                <div class="flex-1">
                                                                    <div class="flex items-center mb-2">
                                                                        <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                                                                        <h4 class="font-medium text-gray-900">{{ $hint->title }}</h4>
                                                                    </div>
                                                                    @if(in_array($hint->id, $userHints))
                                                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                                            @if($hint->content_type === 'text')
                                                                                <!-- Text Only Hint -->
                                                                                <div class="prose prose-sm max-w-none">
                                                                                    <p class="text-sm text-green-800 whitespace-pre-wrap">{{ $hint->content }}</p>
                                                                                </div>
                                                                            @elseif($hint->content_type === 'video')
                                                                                <!-- Video Only Hint -->
                                                                                @if($hint->video_path)
                                                                                    <div class="video-container">
                                                                                        <div class="flex items-center mb-3">
                                                                                            <i class="fas fa-play-circle text-green-600 mr-2"></i>
                                                                                            <span class="text-sm font-medium text-green-800">Video Tutorial</span>
                                                                                        </div>
                                                                                        <video controls class="w-full rounded-lg shadow-sm bg-black" style="max-height: 400px;">
                                                                                            <source src="{{ asset('storage/' . $hint->video_path) }}" type="video/mp4">
                                                                                            <source src="{{ asset('storage/' . $hint->video_path) }}" type="video/webm">
                                                                                            <source src="{{ asset('storage/' . $hint->video_path) }}" type="video/ogg">
                                                                                            Browser Anda tidak mendukung video player.
                                                                                        </video>
                                                                                        <div class="mt-2 text-xs text-green-600">
                                                                                            <i class="fas fa-file-video mr-1"></i>
                                                                                            {{ $hint->video_name }} ({{ $hint->getFormattedVideoSizeAttribute() }})
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @elseif($hint->content_type === 'both')
                                                                                <!-- Text + Video Hint -->
                                                                                @if($hint->content)
                                                                                    <div class="prose prose-sm max-w-none mb-4">
                                                                                        <p class="text-sm text-green-800 whitespace-pre-wrap">{{ $hint->content }}</p>
                                                                                    </div>
                                                                                @endif
                                                                                @if($hint->video_path)
                                                                                    <div class="video-container border-t border-green-200 pt-4">
                                                                                        <div class="flex items-center mb-3">
                                                                                            <i class="fas fa-play-circle text-green-600 mr-2"></i>
                                                                                            <span class="text-sm font-medium text-green-800">Video Tutorial</span>
                                                                                        </div>
                                                                                        <video controls class="w-full rounded-lg shadow-sm bg-black" style="max-height: 400px;">
                                                                                            <source src="{{ asset('storage/' . $hint->video_path) }}" type="video/mp4">
                                                                                            <source src="{{ asset('storage/' . $hint->video_path) }}" type="video/webm">
                                                                                            <source src="{{ asset('storage/' . $hint->video_path) }}" type="video/ogg">
                                                                                            Browser Anda tidak mendukung video player.
                                                                                        </video>
                                                                                        <div class="mt-2 text-xs text-green-600">
                                                                                            <i class="fas fa-file-video mr-1"></i>
                                                                                            {{ $hint->video_name }} ({{ $hint->getFormattedVideoSizeAttribute() }})
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    @else
                                                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                                                            <p class="text-sm text-gray-500 italic flex items-center">
                                                                                <i class="fas fa-lock mr-2"></i>
                                                                                Hint ini tersembunyi. Beli untuk melihat isinya.
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="ml-4 flex flex-col items-end">
                                                                    @if(!in_array($hint->id, $userHints))
                                                                        <button onclick="purchaseHint({{ $hint->id }}, {{ $hint->cost }})" 
                                                                                class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition-colors duration-200 shadow-sm">
                                                                            <i class="fas fa-coins mr-2"></i>Beli ({{ $hint->cost }} poin)
                                                                        </button>
                                                                    @else
                                                                        <div class="px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-lg border border-green-300">
                                                                            <i class="fas fa-check-circle mr-2"></i>Sudah Dibeli
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Completion Celebration -->
                    @if($isCompleted ?? false)
                        <div class="mt-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-xl shadow-lg text-white">
                            <div class="p-8 text-center">
                                <div class="text-6xl mb-4">🎉</div>
                                <h2 class="text-2xl font-bold mb-2">Selamat!</h2>
                                <p class="text-lg mb-4">Anda telah menyelesaikan challenge "{{ $challenge->title }}"</p>
                                <div class="flex items-center justify-center space-x-6 text-sm">
                                    <div class="flex items-center">
                                        <i class="fas fa-trophy mr-2"></i>
                                        <span>Challenge Selesai</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star mr-2"></i>
                                        <span>{{ $challenge->getUserPoints(auth()->user()) }} poin diperoleh</span>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('challenges.index') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-medium">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Challenges
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
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

        // Purchase hint function
        function purchaseHint(hintId, cost) {
            const userPoints = {{ auth()->user()->points ?? 0 }};
            
            if (userPoints < cost) {
                Swal.fire({
                    icon: 'error',
                    title: 'Poin Tidak Mencukupi!',
                    text: `Anda memerlukan ${cost} poin untuk membeli hint ini. Poin Anda saat ini: ${userPoints}`,
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Beli Hint?',
                text: `Apakah Anda yakin ingin membeli hint ini dengan ${cost} poin?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Beli',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Make AJAX request
                    fetch(`/challenges/hints/${hintId}/purchase`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
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
                            text: 'Terjadi kesalahan saat memproses permintaan',
                            confirmButtonText: 'OK'
                        });
                    });
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
    </script>
</body>
</html>
