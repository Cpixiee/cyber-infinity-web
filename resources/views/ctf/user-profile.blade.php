<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - {{ $user->name }} - Cyber Infinity</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen bg-gray-50">
        <!-- Simple Header with Back Button -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('ctf.leaderboard', $ctf) }}" 
                           class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Leaderboard
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('ctf.show', $ctf) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-flag mr-2"></i>Lihat Soal
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Title -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900">Profile - {{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">Statistik peserta di {{ $ctf->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- User Profile Sidebar -->
                <div class="lg:col-span-4 xl:col-span-3">
                    <!-- User Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                        <div class="text-center">
                            @if($user->avatar)
                                <div class="w-20 h-20 mx-auto mb-4">
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                </div>
                            @else
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-gray-200">
                                    <span class="text-2xl font-bold text-gray-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                            <p class="text-gray-600 mb-4">{{ $user->username ? '@' . $user->username : '@' . strtolower(str_replace(' ', '', $user->name)) }}</p>
                            
                            <!-- Rank Badge -->
                            @if($userStats['rank'])
                                <div class="inline-flex items-center px-4 py-2 rounded-lg mb-4 
                                    @if($userStats['rank'] === 1) bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @elseif($userStats['rank'] === 2) bg-gray-50 text-gray-700 border border-gray-200
                                    @elseif($userStats['rank'] === 3) bg-orange-50 text-orange-700 border border-orange-200
                                    @else bg-blue-50 text-blue-700 border border-blue-200 @endif">
                                    @if($userStats['rank'] === 1)
                                        <i class="fas fa-trophy mr-2"></i>
                                    @elseif($userStats['rank'] === 2)
                                        <i class="fas fa-medal mr-2"></i>
                                    @elseif($userStats['rank'] === 3)
                                        <i class="fas fa-award mr-2"></i>
                                    @else
                                        <i class="fas fa-hashtag mr-2"></i>
                                    @endif
                                    <span class="font-semibold">Peringkat #{{ $userStats['rank'] }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <div class="text-2xl font-bold text-gray-900">{{ $userStats['points'] }}</div>
                                <div class="text-sm text-gray-600 font-medium">Points</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <div class="text-2xl font-bold text-gray-900">{{ $userStats['solved'] }}</div>
                                <div class="text-sm text-gray-600 font-medium">Solved</div>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            Statistik Keseluruhan
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 font-medium">Total Lab Points</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $user->points ?? 0 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 font-medium">Total CTF Points</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $user->ctf_points ?? 0 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 font-medium">CTF Solves</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $user->total_ctf_solves ?? 0 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600 font-medium">Member Since</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $user->created_at ? $user->created_at->format('M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:col-span-8 xl:col-span-9">
                    <!-- Performance Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">{{ $userStats['points'] }}</div>
                                    <div class="text-sm text-gray-600 font-medium">Total Points</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">{{ $userStats['solved'] }}</div>
                                    <div class="text-sm text-gray-600 font-medium">Challenges Solved</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $userStats['rank'] ? '#' . $userStats['rank'] : '-' }}
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">Current Rank</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $totalChallenges > 0 ? round(($userStats['solved'] / $totalChallenges) * 100) : 0 }}%
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">Completion Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Solved Challenges -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                Soal yang Diselesaikan ({{ $solvedChallenges->count() }})
                            </h2>
                        </div>

                        @if($solvedChallenges->count() > 0)
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($solvedChallenges as $challenge)
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-sm font-semibold text-gray-900">{{ $challenge->title }}</h3>
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-md">
                                                    {{ $challenge->category }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">
                                                    @if($challenge->submissions && $challenge->submissions->first() && $challenge->submissions->first()->created_at)
                                                        {{ $challenge->submissions->first()->created_at->format('d M Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                                <div class="flex items-center">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $challenge->points }} pts</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <div class="w-4 h-4 bg-gray-400 rounded-full"></div>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Soal yang Diselesaikan</h3>
                                <p class="text-gray-600">{{ $user->name }} belum menyelesaikan soal apapun</p>
                            </div>
                        @endif
                    </div>

                    <!-- Submission History -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                Riwayat Submission ({{ $submissions->count() }})
                            </h2>
                        </div>

                        @if($submissions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenge</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flag</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($submissions as $submission)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $submission->challenge->title }}</div>
                                                        <div class="text-sm text-gray-500">{{ $submission->challenge->category }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">{{ Str::limit($submission->submitted_flag, 30) }}</code>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($submission->status === 'correct')
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-50 text-green-700 rounded-md border border-green-200">
                                                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></div>
                                                            Correct
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-50 text-red-700 rounded-md border border-red-200">
                                                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-2"></div>
                                                            Wrong
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($submission->status === 'correct')
                                                        <span class="text-sm font-semibold text-gray-900">+{{ $submission->points_earned }}</span>
                                                    @else
                                                        <span class="text-sm text-gray-500">0</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                    {{ $submission->created_at ? $submission->created_at->format('d M Y H:i') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <div class="w-4 h-4 bg-gray-400 rounded-full"></div>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Submission</h3>
                                <p class="text-gray-600">{{ $user->name }} belum melakukan submission apapun</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>
</html>