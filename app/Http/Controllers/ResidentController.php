<?php

namespace App\Http\Controllers;

use App\Models\Complaint; // ✅ Tama na ito
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        Complaint::create([  // ✅ Binago dito
            'user_id'        => Auth::id(),
            'full_name'      => $request->full_name,
            'contact_number' => $request->contact_number,
            'category'       => $request->category,
            'location'       => $request->location,
            'description'    => $request->description,
            'photo'          => $photoPath,
            'status'         => 'pending',
        ]);

        return redirect('/resident/complaints/create')
            ->with('success', 'Your complaint has been submitted successfully!');
    }

    public function myComplaints()
    {
        $complaints = Complaint::where('user_id', Auth::id())  // ✅ Binago dito
            ->latest()
            ->get();

        $unreadNotifications = 0;

        return view('resident.mycomplaints', compact('complaints', 'unreadNotifications'));
    }
}