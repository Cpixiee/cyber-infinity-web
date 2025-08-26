<?php

namespace App\Http\Controllers;

use App\Models\Ctf;
use App\Models\CtfChallenge;
use App\Models\CtfSubmission;
use App\Models\CtfHintPurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CtfController extends Controller
{
    // Public CTF Methods
    public function index()
    {
        $now = Carbon::now();
        
        // CTF yang sedang berlangsung (sudah dimulai dan belum berakhir)
        $activeCtfs = Ctf::where('status', 'active')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->orderBy('start_time')
            ->get();

        // CTF yang akan datang (belum dimulai) - hanya tampilkan jika tidak ada yang aktif
        $upcomingCtfs = collect();
        if ($activeCtfs->count() == 0) {
            $upcomingCtfs = Ctf::where('status', 'active')
                ->where('start_time', '>', $now)
                ->orderBy('start_time')
                ->limit(3)
                ->get();
        }

        // CTF yang sudah selesai
        $endedCtfs = Ctf::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->orderByDesc('end_time')
            ->limit(5)
            ->get();

        return view('ctf.index', compact('activeCtfs', 'upcomingCtfs', 'endedCtfs'));
    }



    public function show(Ctf $ctf)
    {
        // Check if CTF is accessible
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($ctf->status === 'draft' && (!$user || !$user->isAdmin())) {
            abort(404);
        }
        $challenges = $ctf->challenges()
            ->where('status', 'active')
            ->get()
            ->groupBy('category');

        $userSubmissions = [];
        $userHints = [];
        $userStats = null;

        if ($user) {
            // Get user's submissions for this CTF
            $userSubmissions = CtfSubmission::where('user_id', $user->id)
                ->where('ctf_id', $ctf->id)
                ->where('status', 'correct')
                ->pluck('ctf_challenge_id')
                ->toArray();

            // Get user's purchased hints
            $userHints = CtfHintPurchase::where('user_id', $user->id)
                ->whereIn('ctf_challenge_id', $ctf->challenges->pluck('id'))
                ->get()
                ->groupBy('ctf_challenge_id')
                ->map(function ($purchases) {
                    return $purchases->pluck('hint_index')->toArray();
                })
                ->toArray();

            // Get user stats for this CTF
            $userStats = [
                'points' => $ctf->submissions()
                    ->where('user_id', $user->id)
                    ->where('status', 'correct')
                    ->sum('points_earned'),
                'solved' => count($userSubmissions),
                'rank' => $ctf->getUserRank($user)
            ];
        }

        // Get leaderboard (top 10)
        $leaderboard = $ctf->getLeaderboard(10);

        return view('ctf.show', compact('ctf', 'challenges', 'userSubmissions', 'userHints', 'userStats', 'leaderboard'));
    }

    public function leaderboard(Ctf $ctf)
    {
        $leaderboard = $ctf->getLeaderboard(100); // Get top 100
        $totalChallenges = $ctf->challenges()->where('status', 'active')->count();
        
        // Get chart data for this specific CTF
        $chartData = $this->getCtfLeaderboardChartData($ctf);
        
        // Get CTF stats
        $totalParticipants = CtfSubmission::where('ctf_id', $ctf->id)
            ->distinct('user_id')
            ->count();
            
        $totalSolves = CtfSubmission::where('ctf_id', $ctf->id)
            ->where('status', 'correct')
            ->count();
        
        return view('ctf.leaderboard', compact(
            'ctf', 
            'leaderboard', 
            'totalChallenges',
            'chartData',
            'totalParticipants',
            'totalSolves'
        ));
    }

    private function getCtfLeaderboardChartData(Ctf $ctf)
    {
        // Get top 10 users for this CTF
        $topUsers = $ctf->getLeaderboard(10);

        $chartData = [];
        
        foreach ($topUsers as $userData) {
            $user = $userData['user'];
            
            // Get user's solve progression for this specific CTF
            $submissions = CtfSubmission::where('user_id', $user->id)
                ->whereHas('challenge', function($query) use ($ctf) {
                    $query->where('ctf_id', $ctf->id);
                })
                ->where('status', 'correct')
                ->with('challenge')
                ->orderBy('submitted_at')
                ->get();

            $userProgress = [];
            $cumulativePoints = 0;
            
            foreach ($submissions as $submission) {
                $cumulativePoints += $submission->challenge->points;
                $userProgress[] = [
                    'date' => $submission->submitted_at->format('Y-m-d H:i'),
                    'points' => $cumulativePoints
                ];
            }

            if (!empty($userProgress)) {
                $chartData[] = [
                    'name' => $user->username,
                    'data' => $userProgress
                ];
            }
        }

        return $chartData;
    }

    public function userProfile(Ctf $ctf, User $user)
    {
        // Get user's performance in this CTF
        $userStats = [
            'points' => $ctf->submissions()
                ->where('user_id', $user->id)
                ->where('status', 'correct')
                ->sum('points_earned'),
            'rank' => $ctf->getUserRank($user),
            'solved_challenges' => $ctf->submissions()
                ->where('user_id', $user->id)
                ->where('status', 'correct')
                ->with('challenge')
                ->get(),
            'total_attempts' => $ctf->submissions()
                ->where('user_id', $user->id)
                ->count(),
        ];

        // Get overall CTF stats
        $overallStats = $user->getCtfStats();

        return view('ctf.user-profile', compact('ctf', 'user', 'userStats', 'overallStats'));
    }

    public function submitFlag(Request $request, Ctf $ctf, CtfChallenge $challenge)
    {
        $request->validate([
            'flag' => 'required|string|max:255'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check if CTF is active
        if (!$ctf->isActive()) {
            return response()->json(['success' => false, 'message' => 'CTF is not active']);
        }

        // Check if user can attempt this challenge
        if (!$challenge->canUserAttempt($user)) {
            return response()->json(['success' => false, 'message' => 'Cannot attempt this challenge']);
        }

        $submittedFlag = $request->flag;
        $isCorrect = $challenge->validateFlag($submittedFlag);

        DB::transaction(function () use (&$user, $ctf, $challenge, $submittedFlag, $isCorrect) {
            // Create submission record
            CtfSubmission::create([
                'user_id' => $user->id,
                'ctf_id' => $ctf->id,
                'ctf_challenge_id' => $challenge->id,
                'submitted_flag' => $submittedFlag,
                'status' => $isCorrect ? 'correct' : 'incorrect',
                'points_earned' => $isCorrect ? $challenge->points : 0,
                'submitted_at' => Carbon::now(),
            ]);

            if ($isCorrect) {
                // Update user's CTF points
                $user->ctf_points = ($user->ctf_points ?? 0) + $challenge->points;
                $user->total_ctf_solves = ($user->total_ctf_solves ?? 0) + 1;
                $user->save();

                // Increment challenge solve count
                $challenge->incrementSolveCount();
            }
        });

        if ($isCorrect) {
            return response()->json([
                'success' => true,
                'message' => "🎉 Correct! Flag accepted. (+{$challenge->points} points)",
                'points' => $challenge->points,
                'isFirstSolve' => $challenge->solve_count === 1
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '❌ Incorrect flag, try again!'
        ]);
    }

    public function purchaseHint(Request $request, CtfChallenge $challenge)
    {
        $request->validate([
            'hint_index' => 'required|integer|min:0'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $hintIndex = $request->hint_index;
        $hints = $challenge->hints;

        // Check if hint exists
        if (!$hints || !isset($hints[$hintIndex])) {
            return response()->json(['success' => false, 'message' => 'Hint not found']);
        }

        $hint = $hints[$hintIndex];
        $cost = $hint['cost'] ?? 10;

        // Check if already purchased
        $alreadyPurchased = CtfHintPurchase::where('user_id', $user->id)
            ->where('ctf_challenge_id', $challenge->id)
            ->where('hint_index', $hintIndex)
            ->exists();

        if ($alreadyPurchased) {
            return response()->json(['success' => false, 'message' => 'Hint already purchased']);
        }

        // Check if user has enough CTF points
        if (($user->ctf_points ?? 0) < $cost) {
            return response()->json(['success' => false, 'message' => 'Insufficient CTF points']);
        }

        DB::transaction(function () use (&$user, $challenge, $hintIndex, $cost) {
            // Deduct CTF points
            $user->ctf_points = ($user->ctf_points ?? 0) - $cost;
            $user->save();

            // Record purchase
            CtfHintPurchase::create([
                'user_id' => $user->id,
                'ctf_challenge_id' => $challenge->id,
                'hint_index' => $hintIndex,
                'cost_paid' => $cost,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Hint purchased successfully! (-{$cost} CTF points)",
            'hint' => $hint,
            'user_ctf_points' => $user->ctf_points
        ]);
    }

    // Admin Methods
    public function adminIndex()
    {
        $ctfs = Ctf::with(['challenges', 'creator'])
            ->withCount(['challenges', 'submissions'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.ctf.index', compact('ctfs'));
    }

    public function create()
    {
        return view('admin.ctf.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|max:10240', // 10MB
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|string|in:draft,active,inactive',
            'rules' => 'nullable|array',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $validated['created_by'] = Auth::id();

            // Handle banner image upload
            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $request->file('banner_image')
                    ->store('ctf-banners', 'public');
            }

            Ctf::create($validated);
        });

        return redirect()->route('admin.ctf.index')
            ->with('success', 'CTF created successfully!');
    }

    public function edit(Ctf $ctf)
    {
        return view('admin.ctf.edit', compact('ctf'));
    }

    public function update(Request $request, Ctf $ctf)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|max:10240',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|string|in:draft,active,inactive,ended',
            'rules' => 'nullable|array',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request, $ctf) {
            // Handle banner image upload
            if ($request->hasFile('banner_image')) {
                // Delete old banner if exists
                if ($ctf->banner_image && Storage::disk('public')->exists($ctf->banner_image)) {
                    Storage::disk('public')->delete($ctf->banner_image);
                }
                $validated['banner_image'] = $request->file('banner_image')
                    ->store('ctf-banners', 'public');
            }

            $ctf->update($validated);
        });

        return redirect()->route('admin.ctf.index')
            ->with('success', 'CTF updated successfully!');
    }

    public function destroy(Ctf $ctf)
    {
        DB::transaction(function () use ($ctf) {
            // Delete banner image if exists
            if ($ctf->banner_image && Storage::disk('public')->exists($ctf->banner_image)) {
                Storage::disk('public')->delete($ctf->banner_image);
            }

            // Delete challenge files
            foreach ($ctf->challenges as $challenge) {
                if ($challenge->files) {
                    foreach ($challenge->files as $file) {
                        if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                            Storage::disk('public')->delete($file['path']);
                        }
                    }
                }
            }

            $ctf->delete();
        });

        return redirect()->route('admin.ctf.index')
            ->with('success', 'CTF and all associated data deleted successfully!');
    }

    // Challenge Management
    public function manageChallenges(Ctf $ctf)
    {
        $challenges = $ctf->challenges()
            ->orderBy('category')
            ->orderBy('points')
            ->get();

        return view('admin.ctf.challenges', compact('ctf', 'challenges'));
    }

    public function createChallenge(Ctf $ctf)
    {
        return view('admin.ctf.create-challenge', compact('ctf'));
    }

    public function storeChallenge(Request $request, Ctf $ctf)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'difficulty' => 'required|string|in:Easy,Medium,Hard',
            'description' => 'required|string',
            'points' => 'required|integer|min:1',
            'flag' => 'required|string|max:255',
            'case_sensitive' => 'boolean',
            'status' => 'required|string|in:active,hidden,draft',
            'files.*' => 'nullable|file|max:102400', // 100MB per file
            'hints' => 'nullable|array',
            'hints.*.title' => 'required|string|max:255',
            'hints.*.content' => 'required|string',
            'hints.*.cost' => 'required|integer|min:1|max:1000',
            'max_attempts' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request, $ctf) {
            $validated['ctf_id'] = $ctf->id;
            $validated['created_by'] = Auth::id();

            // Handle file uploads
            $files = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('ctf-files', 'public');
                    $files[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                    ];
                }
            }
            $validated['files'] = $files;

            CtfChallenge::create($validated);
        });

        return redirect()->route('admin.ctf.challenges', $ctf)
            ->with('success', 'Challenge created successfully!');
    }

    public function editChallenge(Ctf $ctf, CtfChallenge $challenge)
    {
        return view('admin.ctf.edit-challenge', compact('ctf', 'challenge'));
    }

    public function updateChallenge(Request $request, Ctf $ctf, CtfChallenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'points' => 'required|integer|min:1',
            'flag' => 'required|string|max:255',
            'case_sensitive' => 'boolean',
            'status' => 'required|string|in:active,hidden,draft',
            'files.*' => 'nullable|file|max:102400',
            'hints' => 'nullable|array',
            'hints.*.title' => 'required|string|max:255',
            'hints.*.content' => 'required|string',
            'hints.*.cost' => 'required|integer|min:1|max:1000',
            'max_attempts' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request, $challenge) {
            // Handle file uploads
            if ($request->hasFile('files')) {
                // Delete old files
                if ($challenge->files) {
                    foreach ($challenge->files as $file) {
                        if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                            Storage::disk('public')->delete($file['path']);
                        }
                    }
                }

                $files = [];
                foreach ($request->file('files') as $file) {
                    $path = $file->store('ctf-files', 'public');
                    $files[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                    ];
                }
                $validated['files'] = $files;
            }

            $challenge->update($validated);
        });

        return redirect()->route('admin.ctf.challenges', $ctf)
            ->with('success', 'Challenge updated successfully!');
    }

    public function destroyChallenge(Ctf $ctf, CtfChallenge $challenge)
    {
        DB::transaction(function () use ($challenge) {
            // Delete challenge files
            if ($challenge->files) {
                foreach ($challenge->files as $file) {
                    if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }

            $challenge->delete();
        });

        return redirect()->route('admin.ctf.challenges', $ctf)
            ->with('success', 'Challenge deleted successfully!');
    }
}
