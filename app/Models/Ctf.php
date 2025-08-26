<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ctf extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'banner_image',
        'start_time',
        'end_time',
        'status',
        'rules',
        'max_participants',
        'created_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'rules' => 'array',
        'max_participants' => 'integer'
    ];

    // Relationships
    public function challenges()
    {
        return $this->hasMany(CtfChallenge::class)->orderBy('category')->orderBy('points');
    }

    public function submissions()
    {
        return $this->hasMany(CtfSubmission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'ctf_submissions', 'ctf_id', 'user_id')
            ->distinct()
            ->select('users.*');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' && 
               Carbon::now()->between($this->start_time, $this->end_time);
    }

    public function hasStarted()
    {
        return Carbon::now()->gte($this->start_time);
    }

    public function hasEnded()
    {
        return Carbon::now()->gt($this->end_time);
    }

    public function getTimeRemaining()
    {
        if (!$this->hasStarted()) {
            return 'Starts ' . $this->start_time->diffForHumans();
        }
        
        if (!$this->hasEnded()) {
            return 'Ends ' . $this->end_time->diffForHumans();
        }
        
        return 'Ended';
    }

    public function getStatus()
    {
        if ($this->status === 'draft') {
            return 'draft';
        }
        
        if ($this->status === 'inactive') {
            return 'inactive';
        }
        
        if ($this->isActive()) {
            return 'live';
        }
        
        if ($this->hasEnded()) {
            return 'ended';
        }
        
        if (!$this->hasStarted() && $this->status === 'active') {
            return 'upcoming';
        }
        
        return 'unknown';
    }

    public function getDuration()
    {
        $start = $this->start_time;
        $end = $this->end_time;
        
        $totalMinutes = $end->diffInMinutes($start);
        
        if ($totalMinutes < 60) {
            return $totalMinutes . ' menit';
        } elseif ($totalMinutes < 1440) { // less than 24 hours
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;
            return $hours . ' jam' . ($minutes > 0 ? ' ' . $minutes . ' menit' : '');
        } else {
            $days = floor($totalMinutes / 1440);
            $hours = floor(($totalMinutes % 1440) / 60);
            return $days . ' hari' . ($hours > 0 ? ' ' . $hours . ' jam' : '');
        }
    }

    public function getLeaderboard($limit = 10)
    {
        return User::select('users.id', 'users.name', 'users.username', 'users.email', 'users.avatar', 'users.created_at', 'users.updated_at')
            ->selectRaw('SUM(ctf_submissions.points_earned) as total_points')
            ->selectRaw('COUNT(DISTINCT ctf_submissions.ctf_challenge_id) as solved_challenges')
            ->selectRaw('MIN(ctf_submissions.submitted_at) as first_solve_time')
            ->join('ctf_submissions', 'users.id', '=', 'ctf_submissions.user_id')
            ->where('ctf_submissions.ctf_id', $this->id)
            ->where('ctf_submissions.status', 'correct')
            ->whereNotNull('users.id')
            ->groupBy('users.id', 'users.name', 'users.username', 'users.email', 'users.avatar', 'users.created_at', 'users.updated_at')
            ->orderByDesc('total_points')
            ->orderBy('first_solve_time')
            ->limit($limit)
            ->get();
    }

    public function getUserRank($user)
    {
        if (!$user) return null;

        $userPoints = $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'correct')
            ->sum('points_earned');

        if ($userPoints == 0) return null;

        $betterUsers = User::select('users.id')
            ->selectRaw('SUM(ctf_submissions.points_earned) as total_points')
            ->selectRaw('MIN(ctf_submissions.submitted_at) as first_solve_time')
            ->join('ctf_submissions', 'users.id', '=', 'ctf_submissions.user_id')
            ->where('ctf_submissions.ctf_id', $this->id)
            ->where('ctf_submissions.status', 'correct')
            ->groupBy('users.id')
            ->havingRaw('total_points > ? OR (total_points = ? AND first_solve_time < (
                SELECT MIN(submitted_at) FROM ctf_submissions 
                WHERE user_id = ? AND ctf_id = ? AND status = "correct"
            ))', [$userPoints, $userPoints, $user->id, $this->id])
            ->count();

        return $betterUsers + 1;
    }

    public function getTotalParticipants()
    {
        return $this->submissions()
            ->distinct('user_id')
            ->count('user_id');
    }

    public function canUserJoin($user)
    {
        if (!$user) return false;
        if (!$this->isActive()) return false;
        if ($this->max_participants && $this->getTotalParticipants() >= $this->max_participants) {
            return false;
        }
        return true;
    }
}
