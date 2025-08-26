<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'title',
        'description',
        'flag',
        'points',
        'order',
        'is_active',
        'external_link',
        'file_path',
        'file_name',
        'file_size'
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'file_size' => 'integer'
    ];

    // Relationship with challenge
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
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

    // Check if user completed this task
    public function isCompletedByUser($user)
    {
        if (!$user) return false;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->exists();
    }

    // Get user's submission for this task
    public function getUserSubmission($user)
    {
        if (!$user) return null;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
    }

    // Check if user can access this task (previous tasks completed)
    public function canUserAccess($user)
    {
        if (!$user) return false;
        
        // Always allow access to first task
        if ($this->order === 1) return true;
        
        // Check if all previous tasks are completed
        $previousTasks = $this->challenge->tasks()
            ->where('order', '<', $this->order)
            ->get();
            
        foreach ($previousTasks as $task) {
            if (!$task->isCompletedByUser($user)) {
                return false;
            }
        }
        
        return true;
    }

    // Validate submitted flag
    public function validateFlag($submittedFlag)
    {
        // Remove whitespace and make comparison case-insensitive
        $submittedFlag = trim($submittedFlag);
        $correctFlag = trim($this->flag);
        
        return strcasecmp($submittedFlag, $correctFlag) === 0;
    }

    // Get attempts count for user
    public function getUserAttemptsCount($user)
    {
        if (!$user) return 0;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->count();
    }

    // Scope for active tasks
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered tasks
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Get formatted file size
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Check if task has file
    public function hasFile()
    {
        return !empty($this->file_path) && !empty($this->file_name);
    }
}
