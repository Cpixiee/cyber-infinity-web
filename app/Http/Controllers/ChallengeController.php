<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeTask;
use App\Models\ChallengeSubmission;
use App\Models\ChallengeHint;
use App\Models\UserHintPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChallengeController extends Controller
{
    // User-facing methods
    public function index(Request $request)
    {
        $query = Challenge::with(['tasks', 'submissions'])
            ->where('status', 'active');
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        
        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $challenges = $query->latest()->paginate(12);
        
        // Get categories and difficulties for filters
        $categories = Challenge::distinct('category')->pluck('category');
        $difficulties = ['Easy', 'Medium', 'Hard'];
        
        return view('challenges.index', compact('challenges', 'categories', 'difficulties'));
    }

    public function show(Challenge $challenge)
    {
        $challenge->load(['tasks' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }, 'hints']);
        
        $user = Auth::user();
        $userProgress = [];
        $userHints = [];
        
        if ($user) {
            // Get user's progress for this challenge
            $userProgress = $challenge->getUserProgress($user);
            
            // Get user's purchased hints
            $userHints = UserHintPurchase::where('user_id', $user->id)
                ->whereIn('challenge_hint_id', $challenge->hints->pluck('id'))
                ->pluck('challenge_hint_id')
                ->toArray();
        }
        
        return view('challenges.show', compact('challenge', 'userProgress', 'userHints'));
    }

    public function submitFlag(Request $request, Challenge $challenge, ChallengeTask $task)
    {
        $request->validate([
            'flag' => 'required|string|max:255|regex:/^[a-zA-Z0-9_\-{}]+$/'
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user can access this task
        if (!$task->canUserAccess($user)) {
            return back()->with('error', 'Anda harus menyelesaikan task sebelumnya terlebih dahulu!');
        }
        
        // Rate limiting for flag submissions
        $key = 'flag_attempts:' . $user->id . ':' . $task->id;
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 10) { // Max 10 attempts per task per hour
            return back()->with('error', 'Terlalu banyak percobaan. Silakan coba lagi dalam 1 jam.');
        }

        $submittedFlag = trim($request->flag);
        $isCorrect = $task->validateFlag($submittedFlag);
        
        // Create submission record
        $submission = ChallengeSubmission::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'challenge_task_id' => $task->id,
            'submitted_flag' => $submittedFlag,
            'status' => $isCorrect ? 'correct' : 'incorrect',
            'points_earned' => $isCorrect ? $task->points : 0,
        ]);
        
        if ($isCorrect) {
            // Clear attempts on success
            cache()->forget($key);
            
            // Add points to user
            $user->points = ($user->points ?? 0) + $task->points;
            $user->save();
            
            // Check if challenge is completed
            if ($challenge->isCompletedByUser($user)) {
                return back()->with('success', "🎉 Benar! Flag diterima. Challenge selesai! (+{$task->points} poin)");
            }
            
            return back()->with('success', "✅ Benar! Flag diterima. (+{$task->points} poin)");
        }
        
        // Increment failed attempts (1 hour expiry)
        cache()->put($key, $attempts + 1, 3600);
        
        return back()->with('error', '❌ Flag salah, coba lagi!');
    }

    public function purchaseHint(Request $request, ChallengeHint $hint)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        // Check if already purchased
        if ($hint->isPurchasedByUser($user)) {
            return response()->json(['success' => false, 'message' => 'Hint sudah dibeli sebelumnya']);
        }
        
        // Check if user has enough points
        if ($user->points < $hint->cost) {
            return response()->json(['success' => false, 'message' => 'Poin tidak mencukupi']);
        }
        
        DB::transaction(function() use ($user, $hint) {
            // Deduct points
            $user->points = ($user->points ?? 0) - $hint->cost;
            $user->save();
            
            // Record purchase
            UserHintPurchase::create([
                'user_id' => $user->id,
                'challenge_hint_id' => $hint->id,
                'cost_paid' => $hint->cost,
            ]);
        });
        
        return response()->json([
            'success' => true, 
            'message' => "Hint berhasil dibeli! (-{$hint->cost} poin)",
            'hint_content' => $hint->content,
            'user_points' => $user->refresh()->points
        ]);
    }

    // Admin methods
    public function adminIndex()
    {
        $challenges = Challenge::with(['tasks', 'creator'])
            ->withCount('submissions')
            ->latest()
            ->paginate(15);
            
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        $categories = ['Web', 'Crypto', 'Forensic', 'OSINT', 'Reverse', 'Pwn', 'Linux'];
        $difficulties = ['Easy', 'Medium', 'Hard'];
        
        return view('admin.challenges.create', compact('categories', 'difficulties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:50',
            'difficulty' => 'required|string|in:Easy,Medium,Hard',
            'points' => 'required|integer|min:0',
            'external_link' => 'nullable|url',
            'status' => 'required|string|in:active,inactive,draft',
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'required|string',
            'tasks.*.flag' => 'required|string|max:255',
            'tasks.*.points' => 'required|integer|min:0',
            'tasks.*.order' => 'required|integer|min:1',
            'tasks.*.external_link' => 'nullable|url',
            'tasks.*.file' => 'nullable|file|max:51200|mimes:zip,rar,txt,pdf,jpg,jpeg,png,gif', // 50MB max, specific mime types
            'tasks.*.hints' => 'nullable|array',
            'tasks.*.hints.*.title' => 'required|string|max:255',
            'tasks.*.hints.*.content' => 'nullable|string',
            'tasks.*.hints.*.content_type' => 'required|in:text,video,both',
            'tasks.*.hints.*.video' => 'nullable|file|mimetypes:video/*|max:51200', // 50MB
            'tasks.*.hints.*.cost' => 'required|integer|min:1',
            'tasks.*.hints.*.order' => 'required|integer|min:1',
        ]);

        DB::transaction(function() use ($validated, $request) {
            $validated['created_by'] = Auth::id();
            
            // Create challenge
            $challenge = Challenge::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'difficulty' => $validated['difficulty'],
                'points' => $validated['points'],
                'external_link' => $validated['external_link'],
                'status' => $validated['status'],
                'created_by' => $validated['created_by'],
            ]);

            // Create tasks
            foreach ($validated['tasks'] as $taskIndex => $taskData) {
                // Handle file upload
                $filePath = null;
                $fileName = null;
                $fileSize = null;
                
                if ($request->hasFile("tasks.{$taskIndex}.file")) {
                    $file = $request->file("tasks.{$taskIndex}.file");
                    $fileName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                    
                    // Sanitize filename
                    $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
                    
                    // Generate unique filename to prevent conflicts
                    $uniqueName = time() . '_' . $fileName;
                    
                    // Store file in storage/app/public/challenge-files
                    $filePath = $file->storeAs('challenge-files', $uniqueName, 'public');
                }
                
                $task = ChallengeTask::create([
                    'challenge_id' => $challenge->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'flag' => $taskData['flag'],
                    'points' => $taskData['points'],
                    'order' => $taskData['order'],
                    'external_link' => $taskData['external_link'] ?? null,
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'is_active' => true,
                ]);

                // Create hints if provided
                if (isset($taskData['hints']) && is_array($taskData['hints'])) {
                    foreach ($taskData['hints'] as $hintIndex => $hintData) {
                        // Handle video upload for hint
                        $videoPath = null;
                        $videoName = null;
                        $videoSize = null;
                        
                        if ($request->hasFile("tasks.{$taskIndex}.hints.{$hintIndex}.video")) {
                            $video = $request->file("tasks.{$taskIndex}.hints.{$hintIndex}.video");
                            $videoName = $video->getClientOriginalName();
                            $videoSize = $video->getSize();
                            
                            // Store video in storage/app/public/challenge-hints
                            $videoPath = $video->store('challenge-hints', 'public');
                        }
                        
                        ChallengeHint::create([
                            'challenge_id' => $challenge->id,
                            'challenge_task_id' => $task->id,
                            'title' => $hintData['title'],
                            'content' => $hintData['content'] ?? null,
                            'content_type' => $hintData['content_type'],
                            'video_path' => $videoPath,
                            'video_name' => $videoName,
                            'video_size' => $videoSize,
                            'cost' => $hintData['cost'],
                            'order' => $hintData['order'],
                            'is_active' => true,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge berhasil dibuat dengan ' . count($validated['tasks']) . ' tasks!');
    }

    public function edit(Challenge $challenge)
    {
        $categories = ['Web', 'Crypto', 'Forensic', 'OSINT', 'Reverse', 'Pwn', 'Linux'];
        $difficulties = ['Easy', 'Medium', 'Hard'];
        
        return view('admin.challenges.edit', compact('challenge', 'categories', 'difficulties'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:50',
            'difficulty' => 'required|string|in:Easy,Medium,Hard',
            'points' => 'required|integer|min:0',
            'external_link' => 'nullable|url',
            'status' => 'required|string|in:active,inactive,draft',
        ]);

        $challenge->update($validated);

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge berhasil diperbarui!');
    }

    public function destroy(Challenge $challenge)
    {
        // Delete all associated files before deleting challenge
        foreach ($challenge->tasks as $task) {
            // Delete task file if exists
            if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
                Storage::disk('public')->delete($task->file_path);
            }
            
            // Delete hint videos if exist
            foreach ($task->hints as $hint) {
                if ($hint->video_path && Storage::disk('public')->exists($hint->video_path)) {
                    Storage::disk('public')->delete($hint->video_path);
                }
            }
        }
        
        $challenge->delete();
        
        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge dan semua file terkait berhasil dihapus!');
    }

    // Task management methods
    public function manageTasks(Challenge $challenge)
    {
        $tasks = $challenge->tasks()->orderBy('order')->get();
        
        return view('admin.challenges.tasks', compact('challenge', 'tasks'));
    }

    public function storeTask(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'flag' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
            'order' => 'required|integer|min:1',
        ]);

        $validated['challenge_id'] = $challenge->id;
        
        ChallengeTask::create($validated);

        return back()->with('success', 'Task berhasil ditambahkan!');
    }

    public function updateTask(Request $request, ChallengeTask $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'flag' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task berhasil diperbarui!');
    }

    public function destroyTask(ChallengeTask $task)
    {
        // Delete task file if exists
        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            Storage::disk('public')->delete($task->file_path);
        }
        
        // Delete hint videos if exist
        foreach ($task->hints as $hint) {
            if ($hint->video_path && Storage::disk('public')->exists($hint->video_path)) {
                Storage::disk('public')->delete($hint->video_path);
            }
        }
        
        $task->delete();
        
        return back()->with('success', 'Task dan semua file terkait berhasil dihapus!');
    }

    public function downloadTaskFile(ChallengeTask $task)
    {
        // Check if user is authenticated
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        // Check if task has file
        if (!$task->hasFile()) {
            abort(404, 'File not found');
        }

        $filePath = storage_path('app/public/' . $task->file_path);

        // Check if file exists on disk
        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        // Return file download response
        return response()->download($filePath, $task->file_name, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
