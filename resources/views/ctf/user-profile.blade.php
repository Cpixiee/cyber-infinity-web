@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('ctf.leaderboard', $ctf) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Profile - {{ $user->name }}</h1>
                        <p class="text-sm text-gray-600">Statistik peserta di {{ $ctf->name }}</p>
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
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- User Profile -->
                <div class="lg:w-80">
                    <!-- User Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-3xl text-white"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                            <p class="text-gray-600 mb-4">{{ $user->username ? '@' . $user->username : '@' . strtolower(str_replace(' ', '', $user->name)) }}</p>
                            
                            <!-- Rank Badge -->
                            @if($userStats['rank'])
                                <div class="inline-flex items-center px-4 py-2 rounded-full mb-4 {{ $userStats['rank'] <= 3 ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' : 'bg-gray-100 text-gray-800' }}">
                                    @if($userStats['rank'] === 1)
                                        <i class="fas fa-crown mr-2"></i>
                                    @elseif($userStats['rank'] === 2)
                                        <i class="fas fa-medal mr-2"></i>
                                    @elseif($userStats['rank'] === 3)
                                        <i class="fas fa-award mr-2"></i>
                                    @else
                                        <i class="fas fa-hashtag mr-2"></i>
                                    @endif
                                    <span class="font-bold">Rank #{{ $userStats['rank'] }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $userStats['points'] }}</div>
                                <div class="text-xs text-blue-800">CTF Points</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $userStats['solved'] }}</div>
                                <div class="text-xs text-green-800">Solved</div>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                            Statistik Keseluruhan
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Lab Points:</span>
                                <span class="text-sm font-bold text-blue-600">{{ $user->points ?? 0 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total CTF Points:</span>
                                <span class="text-sm font-bold text-purple-600">{{ $user->ctf_points ?? 0 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">CTF Solves:</span>
                                <span class="text-sm font-bold text-green-600">{{ $user->total_ctf_solves ?? 0 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Member Since:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Stats -->
                <div class="flex-1">
                    <!-- Performance Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-bullseye text-blue-600 text-xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">{{ $userStats['points'] }}</div>
                            <div class="text-sm text-gray-600">Total Points</div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">{{ $userStats['solved'] }}</div>
                            <div class="text-sm text-gray-600">Challenges Solved</div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-yellow-600 text-xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">
                                {{ $userStats['rank'] ? '#' . $userStats['rank'] : '-' }}
                            </div>
                            <div class="text-sm text-gray-600">Current Rank</div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-percentage text-purple-600 text-xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">
                                {{ $totalChallenges > 0 ? round(($userStats['solved'] / $totalChallenges) * 100) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-600">Completion Rate</div>
                        </div>
                    </div>

                    <!-- Solved Challenges -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-puzzle-piece text-green-500 mr-2"></i>
                                Soal yang Diselesaikan ({{ $solvedChallenges->count() }})
                            </h2>
                        </div>

                        @if($solvedChallenges->count() > 0)
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($solvedChallenges as $challenge)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-sm font-bold text-gray-900">{{ $challenge->title }}</h3>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $challenge->getCategoryColor() }}">
                                                    {{ $challenge->category }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $challenge->pivot->created_at->format('d M Y H:i') }}
                                                </span>
                                                <span class="text-sm font-bold text-green-600">
                                                    +{{ $challenge->points }} pts
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-puzzle-piece text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Soal yang Diselesaikan</h3>
                                <p class="text-gray-600">{{ $user->name }} belum menyelesaikan soal apapun</p>
                            </div>
                        @endif
                    </div>

                    <!-- Submission History -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-history text-blue-500 mr-2"></i>
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
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>Correct
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times mr-1"></i>Wrong
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($submission->status === 'correct')
                                                        <span class="text-sm font-bold text-green-600">+{{ $submission->points_earned }}</span>
                                                    @else
                                                        <span class="text-sm text-gray-400">0</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                    {{ $submission->created_at->format('d M Y H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-history text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Submission</h3>
                                <p class="text-gray-600">{{ $user->name }} belum melakukan submission apapun</p>
                            </div>
                        @endif
                    </div>
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
@endsection