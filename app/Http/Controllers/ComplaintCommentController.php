<?php
// app/Http/Controllers/ComplaintCommentController.php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintComment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintCommentController extends Controller
{
    /**
     * Resident posts a comment on a specific progress update.
     */
    public function store(Request $request, $complaintId)
    {
        $request->validate([
            'update_index' => 'required|integer|min:0',
            'comment'      => 'required|string|max:1000',
        ]);

        $complaint = Complaint::where('user_id', Auth::id())->findOrFail($complaintId);

        $comment = ComplaintComment::create([
            'complaint_id' => $complaint->id,
            'user_id'      => Auth::id(),
            'update_index' => $request->update_index,
            'comment'      => $request->comment,
        ]);

        $comment->load('user');

        // Notify all admins that a resident commented
        NotificationService::notifyAdminsResidentComment($complaint, $comment->user->name, $request->update_index);

        return response()->json([
            'success' => true,
            'comment' => [
                'id'           => $comment->id,
                'user_name'    => $comment->user->name,
                'comment'      => $comment->comment,
                'update_index' => $comment->update_index,
                'created_at'   => $comment->created_at->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Get all comments for a complaint (admin & resident use).
     */
    public function index($complaintId)
    {
        $user      = Auth::user();
        $complaint = Complaint::findOrFail($complaintId);

        // Only admin or the complaint owner can view comments
        abort_unless($user->role === 'admin' || $complaint->user_id === $user->id, 403);

        $comments = ComplaintComment::where('complaint_id', $complaintId)
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->map(fn($c) => [
                'id'           => $c->id,
                'user_name'    => $c->user->name,
                'comment'      => $c->comment,
                'update_index' => $c->update_index,
                'created_at'   => $c->created_at->toDateTimeString(),
            ]);

        return response()->json(['success' => true, 'comments' => $comments]);
    }
}