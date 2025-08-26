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
        'created_by'
    ];

    protected $casts = [
        'points' => 'integer',
        'status' => 'string'
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
