<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'location',
    'role',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Helpers ────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ── Relationships ──────────────────────────────────────────

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function appNotifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    // ── Profile Helpers ────────────────────────────────────────

    /**
     * Get profile picture URL or null.
     */
    public function getProfilePictureUrl(): ?string
    {
        $pic = $this->profile?->profile_picture;
        return $pic ? asset('storage/' . $pic) : null;
    }

    /**
     * Initials for avatar fallback (up to 2 chars).
     */
    public function getInitials(): string
    {
        $parts = explode(' ', trim($this->name));

        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Unread notification count.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }
}