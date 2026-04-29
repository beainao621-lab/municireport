<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    /**
     * Update profile info + optional new password.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'phone'            => 'nullable|string|max:20',
            'barangay'         => 'nullable|string|max:255',
            'profile_picture'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:8|confirmed',
        ]);

        // ── Password change ─────────────────────────────────────
        if ($request->filled('current_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.',
                ], 422);
            }
            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }
        }

        // ── Basic info ──────────────────────────────────────────
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->barangay = $request->barangay;
        $user->location = $request->barangay;
        $user->save();

        // ── Profile picture ─────────────────────────────────────
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);

        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture = $path;
        }

        $profile->phone    = $request->phone;
        $profile->barangay = $request->barangay;
        $profile->save();

        return response()->json([
            'success'             => true,
            'message'             => 'Profile updated successfully.',
            'profile_picture_url' => $user->getProfilePictureUrl(),
            'name'                => $user->name,
            'initials'            => $user->getInitials(),
        ]);
    }
   public function verifyPassword(Request $request): \Illuminate\Http\JsonResponse
{
    $request->validate(['current_password' => 'required|string']);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Incorrect password. Please try again.'
        ]);
    }

    return response()->json(['success' => true]);
}
}