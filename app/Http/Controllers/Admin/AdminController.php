<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $now   = Carbon::now();
        $total = Complaint::count();

        $stats = [
            'total'           => $total,
            'pending'         => Complaint::where('status', 'Pending')->count(),
            'in_progress'     => Complaint::where('status', 'In Progress')->count(),
            'resolved'        => Complaint::where('status', 'Resolved')->count(),
            'this_week'       => Complaint::whereBetween('created_at', [
                                     $now->copy()->startOfWeek(),
                                     $now->copy()->endOfWeek(),
                                 ])->count(),
            'resolution_rate' => $total > 0
                ? round((Complaint::where('status', 'Resolved')->count() / $total) * 100)
                : 0,
            'citizens'        => User::where('role', 'resident')->count(),
        ];

        $byCategory = Complaint::selectRaw('category, count(*) as total')
                        ->groupBy('category')
                        ->orderByDesc('total')
                        ->get();

        $recent = Complaint::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'byCategory', 'recent'));
    }

    public function complaints(Request $request)
    {
        $query = Complaint::with('user')
            ->where('status', '!=', 'Resolved')
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%{$s}%")
                  ->orWhere('description',    'like', "%{$s}%")
                  ->orWhereHas('user', function ($u) use ($s) {
                      $u->where('name', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Per-page selection: 10, 15, 25, 50
        $perPage    = in_array((int) $request->get('per_page'), [10, 15, 25, 50])
                        ? (int) $request->get('per_page')
                        : 15;

        $complaints = $query->paginate($perPage)->withQueryString();
        $categories = Complaint::distinct()->pluck('category');

        return view('admin.complaints', compact('complaints', 'categories', 'perPage'));
    }

    public function resolved(Request $request)
    {
        $query = Complaint::with('user')
                    ->where('status', 'Resolved')
                    ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%{$s}%")
                  ->orWhere('description',    'like', "%{$s}%")
                  ->orWhereHas('user', function ($u) use ($s) {
                      $u->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $now       = Carbon::now();
        $thisWeek  = Complaint::where('status', 'Resolved')
                        ->whereBetween('updated_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
                        ->count();
        $thisMonth = Complaint::where('status', 'Resolved')
                        ->whereBetween('updated_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                        ->count();

        $perPage    = in_array((int) $request->get('per_page'), [10, 15, 25, 50])
                        ? (int) $request->get('per_page')
                        : 15;

        $complaints = $query->paginate($perPage)->withQueryString();

        return view('admin.resolved', compact('complaints', 'thisWeek', 'thisMonth', 'perPage'));
    }

    public function updateComplaint(Request $request, $id)
    {
        try {
            $complaint = Complaint::findOrFail($id);

            $existingUpdates = is_array($complaint->progress_updates)
                ? $complaint->progress_updates
                : (json_decode($complaint->progress_updates, true) ?? []);

            $newPhotoPaths = [];
            if ($request->hasFile('progress_photos')) {
                foreach ($request->file('progress_photos') as $photo) {
                    $path = $photo->store('complaints/progress', 'public');
                    if ($path) $newPhotoPaths[] = $path;
                }
            }

            if ($request->filled('progress_note') || count($newPhotoPaths) > 0) {
                $existingUpdates[] = [
                    'note'       => $request->progress_note ?? '',
                    'photos'     => $newPhotoPaths,
                    'created_at' => now()->toDateTimeString(),
                ];
            }

            $complaint->assigned_officer = $request->assigned_officer;
            $complaint->status           = $request->status;
            $complaint->remarks          = $request->remarks;
            $complaint->progress_updates = $existingUpdates;

            if ($request->filled('progress_note')) {
                $complaint->progress_note = $request->progress_note;
            }

            if ($request->status === 'Cancelled') {
                $complaint->cancellation_reason = $request->cancellation_reason;
                $complaint->save();
                NotificationService::notifyResidentCancelled($complaint, $request->cancellation_reason ?? 'No reason provided.');
            } else {
                $complaint->cancellation_reason = null;
                $complaint->save();
                NotificationService::notifyResidentStatusUpdate($complaint);
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Complaint #' . $complaint->reference_number . ' updated successfully.',
                'resolved'  => $request->status === 'Resolved',
                'cancelled' => $request->status === 'Cancelled',
            ]);

        } catch (\Exception $e) {
            Log::error('updateComplaint error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a cancelled complaint (admin).
     */
    public function deleteComplaint($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);

            if ($complaint->status !== 'Cancelled') {
                return response()->json(['success' => false, 'message' => 'Only cancelled complaints can be deleted.'], 403);
            }

            $complaint->delete();

            return response()->json(['success' => true, 'message' => 'Complaint deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

  public function citizens(Request $request)
{
    $search = $request->input('search');
 
    $citizens = \App\Models\User::where('role', 'resident')
        // ← BAGONG RULE: Ipakita lang ang mga may kahit isang complaint
        ->whereHas('complaints')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->withCount('complaints')
        ->with(['complaints' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])
        ->orderByDesc('created_at')
        ->paginate(20)
        ->withQueryString();
 
    return view('admin.citizens', compact('citizens'));
}
 

    public function storeCitizen(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'barangay' => 'nullable|string|max:255',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'barangay' => $request->barangay,
            'location' => $request->barangay,
            'password' => bcrypt($request->password),
            'role'     => 'resident',
        ]);

        UserProfile::create(['user_id' => $user->id]);

        return back()->with('success', 'Citizen added successfully.');
    }

    public function updateCitizen(Request $request, $id)
    {
        $citizen = User::where('role', 'resident')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $citizen->id,
            'phone'    => 'nullable|string|max:20',
            'barangay' => 'nullable|string|max:255',
        ]);

        $citizen->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'barangay' => $request->barangay,
            'location' => $request->barangay,
        ]);

        $profile = $citizen->profile ?? new UserProfile(['user_id' => $citizen->id]);
        $profile->phone    = $request->phone;
        $profile->barangay = $request->barangay;
        $profile->save();

        return response()->json(['success' => true, 'message' => 'Citizen updated successfully.']);
    }

    public function reports(Request $request)
    {
        $type = $request->get('type', 'monthly');
        $now  = Carbon::now();

        // Custom date range support
        $customStart = $request->get('date_from');
        $customEnd   = $request->get('date_to');

        if ($customStart && $customEnd) {
            try {
                $start = Carbon::parse($customStart)->startOfDay();
                $end   = Carbon::parse($customEnd)->endOfDay();
                $label = $start->format('M j, Y') . ' – ' . $end->format('M j, Y');
                $type  = 'custom';
            } catch (\Exception $e) {
                $customStart = null;
                $customEnd   = null;
            }
        }

        if (!$customStart || !$customEnd) {
            switch ($type) {
                case 'weekly':
                    $start = $now->copy()->startOfWeek();
                    $end   = $now->copy()->endOfWeek();
                    $label = $start->format('M j') . '–' . $end->format('j, Y');
                    break;
                case 'daily':
                    $start = $now->copy()->startOfDay();
                    $end   = $now->copy()->endOfDay();
                    $label = $now->format('F j, Y');
                    break;
                default:
                    $start = $now->copy()->startOfMonth();
                    $end   = $now->copy()->endOfMonth();
                    $label = $now->format('F Y');
                    $type  = 'monthly';
                    break;
            }
        }

        // Optional category filter
        $filterCategory = $request->get('filter_category', 'all');

        $rowQuery = Complaint::selectRaw("
                    category,
                    SUM(CASE WHEN status = 'Pending'     THEN 1 ELSE 0 END) as new_count,
                    SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_count,
                    SUM(CASE WHEN status = 'Resolved'    THEN 1 ELSE 0 END) as resolved_count,
                    COUNT(*) as total
                ")
                ->whereBetween('created_at', [$start, $end]);

        if ($filterCategory && $filterCategory !== 'all') {
            $rowQuery->where('category', $filterCategory);
        }

        $rows = $rowQuery->groupBy('category')->get();

        $totals = [
            'new'         => $rows->sum('new_count'),
            'in_progress' => $rows->sum('in_progress_count'),
            'resolved'    => $rows->sum('resolved_count'),
            'total'       => $rows->sum('total'),
        ];

        // All categories for dropdown
        $allCategories = Complaint::distinct()->pluck('category');

        return view('admin.reports', compact(
            'rows', 'totals', 'type', 'label',
            'allCategories', 'filterCategory',
            'customStart', 'customEnd'
        ));
    }
}