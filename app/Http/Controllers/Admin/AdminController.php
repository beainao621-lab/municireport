<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
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
        ];

        $byCategory = Complaint::selectRaw('category, count(*) as total')
                        ->groupBy('category')
                        ->orderByDesc('total')
                        ->get();

        $recent = Complaint::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'byCategory', 'recent'));
    }

    // ── Complaints list ────────────────────────────────────────
    public function complaints(Request $request)
    {
        $query = Complaint::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%$s%")
                  ->orWhere('description',    'like', "%$s%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%"));
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(15)->withQueryString();
        $categories = Complaint::distinct()->pluck('category');

        return view('admin.complaints', compact('complaints', 'categories'));
    }

    // ── Assign / Resolve form ──────────────────────────────────
    public function resolve($id)
    {
        $complaint = Complaint::with('user')->findOrFail($id);
        return view('admin.resolve', compact('complaint'));
    }

    public function updateComplaint(Request $request, $id)
    {
        $request->validate([
            'assigned_officer' => 'nullable|string|max:255',
            'status'           => 'required|in:Pending,In Progress,Resolved',
            'remarks'          => 'nullable|string',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'assigned_officer' => $request->assigned_officer,
            'status'           => $request->status,
            'remarks'          => $request->remarks,
        ]);

        return redirect()->route('admin.complaints')
                         ->with('success', 'Complaint #' . $complaint->reference_number . ' updated successfully.');
    }

    // ── Citizens ───────────────────────────────────────────────
    public function citizens(Request $request)
    {
        $query = User::where('role', 'resident')->withCount('complaints')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                                      ->orWhere('email', 'like', "%$s%")
                                      ->orWhere('phone', 'like', "%$s%"));
        }

        $citizens = $query->paginate(15)->withQueryString();
        return view('admin.citizens', compact('citizens'));
    }

    public function storeCitizen(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'barangay' => 'nullable|string|max:255',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'barangay' => $request->barangay,
            'password' => bcrypt($request->password),
            'role'     => 'resident',
        ]);

        return back()->with('success', 'Citizen added successfully.');
    }

    // ── Reports ────────────────────────────────────────────────
    public function reports(Request $request)
    {
        $type = $request->get('type', 'daily');

        [$start, $end, $label] = match ($type) {
            'weekly'  => [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
                'Apr ' . Carbon::now()->startOfWeek()->day . '–' . Carbon::now()->endOfWeek()->day . ', ' . Carbon::now()->year,
            ],
            'monthly' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
                Carbon::now()->format('F Y'),
            ],
            default   => [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay(),
                Carbon::today()->format('F j, Y'),
            ],
        };

        $rows = Complaint::selectRaw('
                    category,
                    SUM(CASE WHEN status = "Pending"     THEN 1 ELSE 0 END) as new_count,
                    SUM(CASE WHEN status = "In Progress" THEN 1 ELSE 0 END) as in_progress_count,
                    SUM(CASE WHEN status = "Resolved"    THEN 1 ELSE 0 END) as resolved_count,
                    COUNT(*) as total
                ')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('category')
                ->get();

        $totals = [
            'new'         => $rows->sum('new_count'),
            'in_progress' => $rows->sum('in_progress_count'),
            'resolved'    => $rows->sum('resolved_count'),
            'total'       => $rows->sum('total'),
        ];

        return view('admin.reports', compact('rows', 'totals', 'type', 'label'));
    }
}