<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty',
        'points',
        'external_link',
        'status',
        'scheduled_at',
        'available_at',
        'created_by'
    ];

    /**
     * The attributes that should not be mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'status' => 'string',
        'scheduled_at' => 'datetime',
        'available_at' => 'datetime'
    ];

    // Relationship with tasks
    public function tasks()
    {
        return $this->hasMany(ChallengeTask::class)->orderBy('order');
    }

    // Relationship with submissions
    public function submissions()
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    // Relationship with hints
    public function hints()
    {
        return $this->hasMany(ChallengeHint::class);
    }

    // Relationship with creator (admin)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get user's progress for this challenge
    public function getUserProgress($user)
    {
        if (!$user) return null;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->with('task')
            ->get();
    }

    // Check if user completed this challenge
    public function isCompletedByUser($user)
    {
        if (!$user) return false;
        
        $totalTasks = $this->tasks()->count();
        $completedTasks = $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->distinct('challenge_task_id')
            ->count();
            
        return $totalTasks > 0 && $completedTasks >= $totalTasks;
    }

    // Get user's total points from this challenge
    public function getUserTotalPoints($user)
    {
        if (!$user) return 0;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->sum('points_earned');
    }

    // Check if challenge is accessible based on scheduling
    public function isAccessible()
    {
        // If status is draft, never accessible to public
        if ($this->status === 'draft') {
            return false;
        }

        // If status is inactive, not accessible
        if ($this->status === 'inactive') {
            return false;
        }

        // If no scheduling set, follow normal active status
        if (!$this->scheduled_at && !$this->available_at) {
            return $this->status === 'active';
        }

        $now = now();

        // If scheduled_at is set, check if it's time
        if ($this->scheduled_at && $now->lt($this->scheduled_at)) {
            return false; // Not yet time
        }

        // If available_at is set, check if still available
        if ($this->available_at && $now->gt($this->available_at)) {
            return false; // No longer available
        }

        return $this->status === 'active';
    }

    // Check if challenge is locked (active but not yet scheduled)
    public function isLocked()
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (!$this->scheduled_at) {
            return false;
        }

        return now()->lt($this->scheduled_at);
    }

    // Get time until challenge becomes available
    public function getTimeUntilAvailable()
    {
        if (!$this->scheduled_at || !$this->isLocked()) {
            return null;
        }

        return $this->scheduled_at->diffForHumans();
    }

    // Get time until challenge expires
    public function getTimeUntilExpiry()
    {
        if (!$this->available_at) {
            return null;
        }

        $now = now();
        if ($now->gt($this->available_at)) {
            return 'Expired';
        }

        return $this->available_at->diffForHumans();
    }

    // Scope for accessible challenges
    public function scopeAccessible($query)
    {
        $now = now();
        
        return $query->where('status', 'active')
            ->where(function($q) use ($now) {
                $q->where(function($subQ) use ($now) {
                    // No scheduling set
                    $subQ->whereNull('scheduled_at')
                         ->whereNull('available_at');
                })
                ->orWhere(function($subQ) use ($now) {
                    // Scheduled but time has come and not expired
                    $subQ->where('scheduled_at', '<=', $now)
                         ->where(function($expQ) use ($now) {
                             $expQ->whereNull('available_at')
                                  ->orWhere('available_at', '>', $now);
                         });
                });
            });
    }

    // Scope for locked challenges (active but not yet scheduled)
    public function scopeLocked($query)
    {
        $now = now();
        
        return $query->where('status', 'active')
            ->where('scheduled_at', '>', $now);
    }

    // Scope for expired challenges (active but past available_at time)
    public function scopeExpired($query)
    {
        $now = now();
        
        return $query->where('status', 'active')
            ->where('available_at', '<', $now);
    }

    // Check if challenge is expired
    public function isExpired()
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (!$this->available_at) {
            return false;
        }

        return now()->gt($this->available_at);
    }
    public function getUserPoints($user)
    {
        if (!$user) return 0;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->sum('points_earned');
    }

    // Get completion percentage for user
    public function getCompletionPercentage($user)
    {
        if (!$user) return 0;
        
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;
        
        $completedTasks = $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->distinct('challenge_task_id')
            ->count();
            
        return round(($completedTasks / $totalTasks) * 100);
    }

    // Get difficulty badge color
    public function getDifficultyColor()
    {
        return match($this->difficulty) {
            'Easy' => 'bg-green-100 text-green-800',
            'Medium' => 'bg-yellow-100 text-yellow-800', 
            'Hard' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Get category badge color
    public function getCategoryColor()
    {
        return match($this->category) {
            'Web' => 'bg-blue-100 text-blue-800',
            'Crypto' => 'bg-purple-100 text-purple-800',
            'Forensic' => 'bg-indigo-100 text-indigo-800',
            'OSINT' => 'bg-cyan-100 text-cyan-800',
            'Reverse' => 'bg-orange-100 text-orange-800',
            'Pwn' => 'bg-red-100 text-red-800',
            'Linux' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scope for active challenges
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for filtering by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for filtering by difficulty
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }
}
