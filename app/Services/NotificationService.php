<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function notifyAdmins(
        string $title,
        string $message,
        string $type        = 'info',
        ?string $link       = null,
        ?int $complaintId   = null
    ): void {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id'      => $admin->id,
                'title'        => $title,
                'message'      => $message,
                'type'         => $type,
                'link'         => $link,
                'complaint_id' => $complaintId,
                'is_read'      => false,
            ]);
        }
    }

    public static function notifyAdminsNewComplaint(Complaint $complaint): void
    {
        self::notifyAdmins(
            title:       'New Complaint Filed',
            message:     "A new complaint #{$complaint->reference_number} has been filed under category: {$complaint->category}.",
            type:        'info',
            link:        '/admin/complaints',
            complaintId: $complaint->id,
        );
    }

    public static function notifyResident(
        int $userId,
        string $title,
        string $message,
        string $type        = 'info',
        ?string $link       = null,
        ?int $complaintId   = null
    ): void {
        Notification::create([
            'user_id'      => $userId,
            'title'        => $title,
            'message'      => $message,
            'type'         => $type,
            'link'         => $link,
            'complaint_id' => $complaintId,
            'is_read'      => false,
        ]);
    }

    public static function notifyResidentStatusUpdate(Complaint $complaint): void
    {
        if (!$complaint->user_id) return;

        $statusMessages = [
            'Pending'     => ['type' => 'info',    'label' => 'received and is pending review'],
            'In Progress' => ['type' => 'warning', 'label' => 'now being addressed'],
            'Resolved'    => ['type' => 'success', 'label' => 'resolved'],
        ];

        $info = $statusMessages[$complaint->status] ?? ['type' => 'info', 'label' => 'updated'];

        self::notifyResident(
            userId:      $complaint->user_id,
            title:       "Complaint #{$complaint->reference_number} — {$complaint->status}",
            message:     "Your complaint has been {$info['label']}." .
                         ($complaint->remarks ? " Remarks: {$complaint->remarks}" : ''),
            type:        $info['type'],
            link:        '/resident/complaints',
            complaintId: $complaint->id,
        );
    }

    public static function notifyResidentNewMessage(Complaint $complaint, string $adminName): void
    {
        if (!$complaint->user_id) return;

        $existing = Notification::where('user_id', $complaint->user_id)
            ->where('complaint_id', $complaint->id)
            ->where('is_read', false)
            ->where('title', 'like', '%New message%')
            ->first();

        if ($existing) {
            $existing->update([
                'message'    => $adminName . ' sent you a new message regarding complaint ' . $complaint->reference_number . '.',
                'updated_at' => now(),
            ]);
        } else {
            Notification::create([
                'user_id'      => $complaint->user_id,
                'title'        => 'New message from the Mayor\'s Office',
                'message'      => $adminName . ' sent you a message regarding complaint ' . $complaint->reference_number . '.',
                'type'         => 'info',
                'link'         => '/resident/messages',
                'complaint_id' => $complaint->id,
                'is_read'      => false,
            ]);
        }
    }

    public static function notifyAdminsResidentMessage(Complaint $complaint, string $residentName): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $existing = Notification::where('user_id', $admin->id)
                ->where('complaint_id', $complaint->id)
                ->where('is_read', false)
                ->where('title', 'like', '%sent a message%')
                ->first();

            if ($existing) {
                $existing->update([
                    'message'    => $residentName . ' sent a new message on complaint ' . $complaint->reference_number . '.',
                    'updated_at' => now(),
                ]);
            } else {
                Notification::create([
                    'user_id'      => $admin->id,
                    'title'        => 'Resident sent a message',
                    'message'      => $residentName . ' sent a message on complaint ' . $complaint->reference_number . '.',
                    'type'         => 'info',
                    'link'         => '/admin/complaints',
                    'complaint_id' => $complaint->id,
                    'is_read'      => false,
                ]);
            }
        }
    }

    public static function notifyResidentCancelled(Complaint $complaint, string $reason): void
    {
        if (!$complaint->user_id) return;

        Notification::create([
            'user_id'      => $complaint->user_id,
            'title'        => 'Complaint Cancelled',
            'message'      => 'Your complaint ' . $complaint->reference_number . ' has been cancelled. Reason: ' . $reason,
            'type'         => 'warning',
            'link'         => '/resident/complaints',
            'complaint_id' => $complaint->id,
            'is_read'      => false,
        ]);
    }

    /**
     * NEW: Notify admins when a resident comments on a progress update.
     * Link goes to admin/complaints so they can open the complaint and see the comment.
     */
    public static function notifyAdminsResidentComment(Complaint $complaint, string $residentName, int $updateIndex): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            // Update existing unread comment notification for same complaint if present
            $existing = Notification::where('user_id', $admin->id)
                ->where('complaint_id', $complaint->id)
                ->where('is_read', false)
                ->where('title', 'like', '%commented%')
                ->first();

            if ($existing) {
                $existing->update([
                    'message'    => $residentName . ' commented on an update for complaint ' . $complaint->reference_number . '.',
                    'updated_at' => now(),
                ]);
            } else {
                Notification::create([
                    'user_id'      => $admin->id,
                    'title'        => 'Resident commented on complaint',
                    'message'      => $residentName . ' commented on an update for complaint ' . $complaint->reference_number . '.',
                    'type'         => 'info',
                    // Link goes to admin complaints — clicking will open that complaint
                    'link'         => '/admin/complaints?highlight=' . $complaint->id,
                    'complaint_id' => $complaint->id,
                    'is_read'      => false,
                ]);
            }
        }
    }
}