<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if notification is read
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    // Mark notification as read
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    // Create login notification
    public static function createLoginNotification($user)
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'login',
            'title' => 'Login Berhasil',
            'message' => 'Anda berhasil login ke Cyber Infinity',
            'data' => [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]
        ]);
    }

    // Create workshop notification
    public static function createWorkshopNotification($type, $title, $message, $workshopId = null, $registrationId = null)
    {
        // Get all users (except admin for new workshops)
        $users = $type === 'workshop_new' 
            ? User::where('role', '!=', 'admin')->get()
            : User::where('id', $registrationId ? WorkshopRegistration::find($registrationId)?->user_id : null)->get();

        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => [
                    'workshop_id' => $workshopId,
                    'registration_id' => $registrationId
                ]
            ]);
        }
    }
}
