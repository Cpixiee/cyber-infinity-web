<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtfHintPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ctf_challenge_id',
        'hint_index',
        'cost_paid'
    ];

    protected $casts = [
        'hint_index' => 'integer',
        'cost_paid' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(CtfChallenge::class, 'ctf_challenge_id');
    }
}
