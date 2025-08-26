<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHintPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_hint_id',
        'cost_paid',
        'purchased_at'
    ];

    protected $casts = [
        'cost_paid' => 'integer',
        'purchased_at' => 'datetime'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with hint
    public function hint()
    {
        return $this->belongsTo(ChallengeHint::class, 'challenge_hint_id');
    }

    // Boot method to set purchased_at
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($purchase) {
            $purchase->purchased_at = now();
        });
    }
}
