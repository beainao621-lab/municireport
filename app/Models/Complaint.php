<?php
// app/Models/Complaint.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_number',
        'full_name',
        'contact_number',
        'category',
        'location',
        'description',
        'photos',
        'status',
        'assigned_officer',
        'remarks',
        'progress_photo',
        'progress_note',
        'progress_photos',
        'progress_updates',
        'cancellation_reason',
    ];

    protected $casts = [
        'photos'           => 'array',
        'progress_photos'  => 'array',
        'progress_updates' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Complaint $complaint) {
            $year  = now()->year;
            $count = static::whereYear('created_at', $year)->count() + 1;
            $complaint->reference_number = 'MR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            if (empty($complaint->status)) {
                $complaint->status = 'Pending';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ComplaintMessage::class);
    }

    // Comments from residents on progress updates
    public function comments()
    {
        return $this->hasMany(ComplaintComment::class);
    }

    public function unreadMessagesForAdmin(): int
    {
        return $this->messages()
            ->where('sender_role', 'resident')
            ->where('is_read', false)
            ->count();
    }

    public function unreadMessagesForResident(): int
    {
        return $this->messages()
            ->where('sender_role', 'admin')
            ->where('is_read', false)
            ->count();
    }
}