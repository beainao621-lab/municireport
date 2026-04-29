<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Return notifications as JSON (for dropdown polling).
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notifications = $user->appNotifications()->latest()->take(30)->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark a single notification as read and return its link.
     */
    public function markRead(Request $request, $id)
    {
        $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'link'    => $notif->link,
        ]);
    }

    /**
     * Mark ALL notifications as read.
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete selected notifications (array of IDs).
     */
    public function deleteSelected(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        Notification::where('user_id', Auth::id())
                    ->whereIn('id', $request->ids)
                    ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a single notification.
     */
    public function destroy($id)
    {
        Notification::where('user_id', Auth::id())->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}