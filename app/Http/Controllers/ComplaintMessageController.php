<?php
// app/Http/Controllers/ComplaintMessageController.php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintMessageController extends Controller
{
    /**
     * Get messages for a complaint (AJAX).
     */
    public function index($complaintId)
    {
        $user      = Auth::user();
        $complaint = Complaint::findOrFail($complaintId);

        // Only the complaint owner or an admin can read messages
        abort_unless($user->role === 'admin' || $complaint->user_id === $user->id, 403);

        // Mark messages as read for the current viewer
        $role = $user->role === 'admin' ? 'resident' : 'admin';
        ComplaintMessage::where('complaint_id', $complaintId)
            ->where('sender_role', $role)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ComplaintMessage::where('complaint_id', $complaintId)
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a message (AJAX).
     */
    public function store(Request $request, $complaintId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $user      = Auth::user();
        $complaint = Complaint::findOrFail($complaintId);

        abort_unless($user->role === 'admin' || $complaint->user_id === $user->id, 403);

        $senderRole = $user->role === 'admin' ? 'admin' : 'resident';

        $msg = ComplaintMessage::create([
            'complaint_id' => $complaintId,
            'sender_id'    => $user->id,
            'sender_role'  => $senderRole,
            'message'      => $request->message,
            'is_read'      => false,
        ]);

        // Notify the other party
        if ($senderRole === 'admin') {
            // Notify resident
            NotificationService::notifyResidentNewMessage($complaint, $user->name);
        } else {
            // Notify all admins
            NotificationService::notifyAdminsResidentMessage($complaint, $user->name);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $msg->id,
                'sender_role' => $msg->sender_role,
                'sender_name' => $user->name,
                'message'     => $msg->message,
                'created_at'  => $msg->created_at->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Unread counts per complaint for admin (used for badge).
     * Returns array of complaint_id => unread_count
     */
    public function adminUnreadCounts()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $counts = ComplaintMessage::where('sender_role', 'resident')
            ->where('is_read', false)
            ->selectRaw('complaint_id, count(*) as cnt')
            ->groupBy('complaint_id')
            ->pluck('cnt', 'complaint_id');

        return response()->json($counts);
    }

    /**
     * Total unread for resident across all their complaints.
     */
    public function residentUnreadCount()
    {
        $user = Auth::user();
        $count = ComplaintMessage::where('sender_role', 'admin')
            ->where('is_read', false)
            ->whereHas('complaint', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return response()->json(['unread' => $count]);
    }
}