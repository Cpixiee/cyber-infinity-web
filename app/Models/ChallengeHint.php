<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeHint extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'challenge_task_id',
        'title',
        'content',
        'cost',
        'order',
        'is_active',
        'video_path',
        'video_name',
        'video_size',
        'content_type'
    ];

    protected $casts = [
        'cost' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'video_size' => 'integer'
    ];

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

    // Relationship with purchases
    public function purchases()
    {
        return $this->hasMany(UserHintPurchase::class);
    }

    // Check if user purchased this hint
    public function isPurchasedByUser($user)
    {
        if (!$user) return false;
        
        return $this->purchases()
            ->where('user_id', $user->id)
            ->exists();
    }

    // Scope for active hints
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered hints
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Check if hint has video
    public function hasVideo()
    {
        return !empty($this->video_path) && !empty($this->video_name);
    }

    // Get formatted video size
    public function getFormattedVideoSizeAttribute()
    {
        if (!$this->video_size) return null;
        
        $bytes = $this->video_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Check if hint is text only
    public function isTextOnly()
    {
        return $this->content_type === 'text';
    }

    // Check if hint is video only
    public function isVideoOnly()
    {
        return $this->content_type === 'video';
    }

    // Check if hint has both text and video
    public function hasBoth()
    {
        return $this->content_type === 'both';
    }
}
