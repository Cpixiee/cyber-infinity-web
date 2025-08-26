<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'avatar',
        'password',
        'birthdate',
        'role',
        'points',
        'ctf_points',
        'total_ctf_solves',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get all workshop registrations for the user
     */
    public function workshopRegistrations()
    {
        return $this->hasMany(WorkshopRegistration::class, 'email', 'email')
                    ->orWhere('nis', $this->nis);
    }

    /**
     * Get all notifications for the user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    /**
     * Check if user is guest
     *
     * @return bool
     */
    public function isGuest()
    {
        return $this->role === 'guest';
    }

    /**
     * Get CTF submissions for the user
     */
    public function ctfSubmissions()
    {
        return $this->hasMany(CtfSubmission::class);
    }

    /**
     * Get CTF hint purchases for the user
     */
    public function ctfHintPurchases()
    {
        return $this->hasMany(CtfHintPurchase::class);
    }

    /**
     * Get CTFs participated by user
     */
    public function participatedCtfs()
    {
        return $this->belongsToMany(Ctf::class, 'ctf_submissions', 'user_id', 'ctf_id')
            ->distinct();
    }

    /**
     * Get user's CTF statistics
     */
    public function getCtfStats()
    {
        return [
            'total_points' => $this->ctf_points ?? 0,
            'total_solves' => $this->total_ctf_solves ?? 0,
            'ctfs_participated' => $this->participatedCtfs()->count(),
            'correct_submissions' => $this->ctfSubmissions()->where('status', 'correct')->count(),
            'total_submissions' => $this->ctfSubmissions()->count(),
        ];
    }

    /**
     * Get user's rank in specific CTF
     */
    public function getCtfRank($ctfId)
    {
        $ctf = Ctf::find($ctfId);
        return $ctf ? $ctf->getUserRank($this) : null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
