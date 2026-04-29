<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ComplaintMessage;

class ResidentController extends Controller
{
    public function showFileComplaint()
    {
        return view('resident.filecomplaint');
    }

    public function storeComplaint(Request $request)
    {
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'category'       => 'required|string',
            'location'       => 'required|string|max:255',
            'description'    => 'required|string',
            'photos'         => 'nullable|array',
            'photos.*'       => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('complaints', 'public');
                if ($path) {
                    $photoPaths[] = $path;
                }
            }
        }

        $complaint = Complaint::create([
            'user_id'        => Auth::id(),
            'full_name'      => $request->full_name,
            'contact_number' => $request->contact_number,
            'category'       => $request->category,
            'location'       => $request->location,
            'description'    => $request->description,
            'photos'         => $photoPaths,
            'status'         => 'Pending',
        ]);

        NotificationService::notifyAdminsNewComplaint($complaint);

        return redirect('/resident/complaints/create')
            ->with('success', 'Your complaint has been submitted successfully!');
    }

    public function myComplaints()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->latest()        // newest first
            ->paginate(10);   // paginated, not scrollable

        return view('resident.mycomplaints', compact('complaints'));
    }

    /**
     * Resident deletes their own cancelled complaint.
     */
    public function deleteComplaint($id)
    {
        $complaint = Complaint::where('user_id', Auth::id())->findOrFail($id);

        if ($complaint->status !== 'Cancelled') {
            return response()->json(['success' => false, 'message' => 'Only cancelled complaints can be deleted.'], 403);
        }

        $complaint->delete();

        return response()->json(['success' => true, 'message' => 'Complaint deleted successfully.']);
    }

    public function messages()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->whereHas('messages')
            ->with(['messages' => function ($q) {
                $q->orderBy('created_at');
            }])
            ->latest()
            ->get();

        foreach ($complaints as $c) {
            ComplaintMessage::where('complaint_id', $c->id)
                ->where('sender_role', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        $totalUnread = 0;

        return view('resident.messages', compact('complaints', 'totalUnread'));
    }
}