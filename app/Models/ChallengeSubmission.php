<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'challenge_task_id',
        'submitted_flag',
        'status',
        'points_earned',
        'submitted_at'
    ];

    protected $casts = [
        'points_earned' => 'integer',
        'submitted_at' => 'datetime'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with challenge
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    // Relationship with task
    public function task()
    {
        return $this->belongsTo(ChallengeTask::class, 'challenge_task_id');
    }

    // Boot method to set submitted_at
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($submission) {
            $submission->submitted_at = now();
        });
    }

    // Scope for correct submissions
    public function scopeCorrect($query)
    {
        return $query->where('status', 'correct');
    }

    // Scope for user submissions
    public function scopeForUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    // Get status badge color
    public function getStatusColor()
    {
        return match($this->status) {
            'correct' => 'bg-green-100 text-green-800',
            'incorrect' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Get status icon
    public function getStatusIcon()
    {
        return match($this->status) {
            'correct' => 'fas fa-check',
            'incorrect' => 'fas fa-times',
            'pending' => 'fas fa-clock',
            default => 'fas fa-question'
        };
    }
}
