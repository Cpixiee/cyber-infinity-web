<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtfChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'ctf_id',
        'title',
        'category',
        'difficulty',
        'description',
        'points',
        'flag',
        'case_sensitive',
        'status',
        'files',
        'hints',
        'max_attempts',
        'solve_count',
        'created_by'
    ];

    protected $casts = [
        'case_sensitive' => 'boolean',
        'files' => 'array',
        'hints' => 'array',
        'points' => 'integer',
        'max_attempts' => 'integer',
        'solve_count' => 'integer'
    ];

    // Relationships
    public function ctf()
    {
        return $this->belongsTo(Ctf::class);
    }

    public function submissions()
    {
        return $this->hasMany(CtfSubmission::class);
    }

    public function hintPurchases()
    {
        return $this->hasMany(CtfHintPurchase::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function validateFlag($submittedFlag)
    {
        if ($this->case_sensitive) {
            return $submittedFlag === $this->flag;
        }
        return strtolower($submittedFlag) === strtolower($this->flag);
    }

    public function isSolvedByUser($user)
    {
        if (!$user) return false;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->exists();
    }

    public function getUserAttempts($user)
    {
        if (!$user) return 0;
        
        return $this->submissions()
            ->where('user_id', $user->id)
            ->count();
    }

    public function canUserAttempt($user)
    {
        if (!$user) return false;
        if ($this->isSolvedByUser($user)) return false;
        if ($this->max_attempts && $this->getUserAttempts($user) >= $this->max_attempts) {
            return false;
        }
        return true;
    }

    public function getHintForUser($user, $hintIndex)
    {
        if (!$user || !$this->hints || !isset($this->hints[$hintIndex])) {
            return null;
        }

        // Check if user has purchased this hint
        $purchased = $this->hintPurchases()
            ->where('user_id', $user->id)
            ->where('hint_index', $hintIndex)
            ->exists();

        if (!$purchased) {
            return [
                'title' => $this->hints[$hintIndex]['title'] ?? 'Hint ' . ($hintIndex + 1),
                'cost' => $this->hints[$hintIndex]['cost'] ?? 10,
                'purchased' => false
            ];
        }

        return array_merge($this->hints[$hintIndex], ['purchased' => true]);
    }

    public function getUserPurchasedHints($user)
    {
        if (!$user) return [];
        
        return $this->hintPurchases()
            ->where('user_id', $user->id)
            ->pluck('hint_index')
            ->toArray();
    }

    public function getCategoryColor()
    {
        $colors = [
            'Web' => 'bg-blue-100 text-blue-800',
            'Crypto' => 'bg-purple-100 text-purple-800',
            'Forensic' => 'bg-green-100 text-green-800',
            'OSINT' => 'bg-yellow-100 text-yellow-800',
            'Reverse' => 'bg-red-100 text-red-800',
            'Pwn' => 'bg-gray-100 text-gray-800',
            'Linux' => 'bg-indigo-100 text-indigo-800',
            'Network' => 'bg-teal-100 text-teal-800',
            'Mobile' => 'bg-pink-100 text-pink-800',
            'Hardware' => 'bg-orange-100 text-orange-800',
        ];

        return $colors[$this->category] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPointsColor()
    {
        if ($this->points <= 100) return 'bg-green-100 text-green-800';
        if ($this->points <= 300) return 'bg-yellow-100 text-yellow-800';
        if ($this->points <= 500) return 'bg-orange-100 text-orange-800';
        return 'bg-red-100 text-red-800';
    }

    public function getDifficultyColor()
    {
        $colors = [
            'Easy' => 'bg-green-100 text-green-800 border-green-200',
            'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 
            'Hard' => 'bg-red-100 text-red-800 border-red-200',
        ];

        return $colors[$this->difficulty] ?? 'bg-gray-100 text-gray-800 border-gray-200';
    }

    public function getDifficultyIcon()
    {
        $icons = [
            'Easy' => 'fas fa-circle text-green-500',
            'Medium' => 'fas fa-circle-half-stroke text-yellow-500',
            'Hard' => 'fas fa-circle text-red-500',
        ];

        return $icons[$this->difficulty] ?? 'fas fa-circle text-gray-500';
    }

    public function getFirstSolver()
    {
        return $this->submissions()
            ->where('status', 'correct')
            ->with('user')
            ->orderBy('submitted_at')
            ->first()?->user;
    }

    public function incrementSolveCount()
    {
        $this->increment('solve_count');
    }
}
