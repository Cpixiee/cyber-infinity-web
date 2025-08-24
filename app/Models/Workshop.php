<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'activity_type',
        'start_date',
        'end_date',
        'start_time',
        'duration',
        'location',
        'target_participants',
        'requirements',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration' => 'float'
    ];

    // Relationship with registrations
    public function registrations()
    {
        return $this->hasMany(WorkshopRegistration::class)->orderBy('created_at', 'desc');
    }

    // Check if a user is already registered using email or NIS
    public function isUserRegistered($email, $nis)
    {
        return $this->registrations()
            ->where(function($query) use ($email, $nis) {
                $query->where('email', $email)
                      ->orWhere('nis', $nis);
            })
            ->exists();
    }

    // Check if a user is registered for this workshop (used in views)
    public function isRegisteredByUser($user)
    {
        if (!$user) return false;
        
        return $this->registrations()
            ->where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nis', $user->nis ?? null);
            })
            ->exists();
    }

    // Check if workshop has available slots
    public function hasAvailableSlots()
    {
        $approvedCount = $this->registrations()->where('status', 'approved')->count();
        return $approvedCount < $this->target_participants;
    }

    // Get registration for specific user
    public function getRegistrationForUser($user)
    {
        if (!$user) return null;
        
        return $this->registrations()
            ->where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nis', $user->nis ?? null);
            })
            ->first();
    }
}
