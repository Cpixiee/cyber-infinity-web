<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtfSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ctf_id',
        'ctf_challenge_id',
        'submitted_flag',
        'status',
        'points_earned',
        'submitted_at'
    ];

    protected $casts = [
        'points_earned' => 'integer',
        'submitted_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ctf()
    {
        return $this->belongsTo(Ctf::class);
    }

    public function challenge()
    {
        return $this->belongsTo(CtfChallenge::class, 'ctf_challenge_id');
    }

    // Helper methods
    public function isCorrect()
    {
        return $this->status === 'correct';
    }

    public function getStatusColor()
    {
        return $this->status === 'correct' 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    public function getStatusIcon()
    {
        return $this->status === 'correct' 
            ? 'fas fa-check-circle' 
            : 'fas fa-times-circle';
    }

    public function isFirstSolve()
    {
        return $this->status === 'correct' && 
               $this->challenge->submissions()
                   ->where('status', 'correct')
                   ->where('submitted_at', '<', $this->submitted_at)
                   ->doesntExist();
    }
}
