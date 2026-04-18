<?php

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
        'photo',
        'status',
        'assigned_officer',
        'remarks',
    ];

    // Automatically generate a reference number on creation
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

    // ── Relationships ──────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ────────────────────────────────────────────────
    public function statusColor(): string
    {
        return match ($this->status) {
            'Pending'     => '#F59E0B',
            'In Progress' => '#3182BD',
            'Resolved'    => '#10B981',
            default       => '#9ECAE1',
        };
    }

    public function statusBg(): string
    {
        return match ($this->status) {
            'Pending'     => '#FEF3C7',
            'In Progress' => '#EFF3FF',
            'Resolved'    => '#D1FAE5',
            default       => '#F3F4F6',
        };
    }
}