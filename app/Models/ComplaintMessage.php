<?php
// app/Models/ComplaintMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintMessage extends Model
{
    protected $fillable = [
        'complaint_id',
        'sender_id',
        'sender_role',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}