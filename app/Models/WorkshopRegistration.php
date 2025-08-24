<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'full_name',
        'class',
        'nis',
        'email',
        'status', // pending, approved, rejected
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with workshop
    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    // Before creating a new registration
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            // Check if user already has a registration
            $existingRegistration = static::where('email', $registration->email)
                ->orWhere('nis', $registration->nis)
                ->first();

            if ($existingRegistration) {
                throw new \Exception('User already registered for a workshop');
            }
        });
    }
}
