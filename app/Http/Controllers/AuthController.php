<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showRegister()
    {
        return view('register');
    }

    // AuthController.php — register method
public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'phone'    => 'required|string|max:20',
        'location' => 'required|string|max:255',
        'password' => 'required|min:6|confirmed',
    ]);

   $user = User::create([
    'name'     => $request->name,
    'email'    => $request->email,
    'phone'    => $request->phone,
    'location' => $request->location,
    'password' => $request->password,
    'role'     => 'resident',
]);

    // ← Create profile record para gumana ang getProfilePictureUrl()
    \App\Models\UserProfile::create([
        'user_id'  => $user->id,
        'phone'    => $request->phone,
        'barangay' => $request->location,
    ]);
    return redirect('/login')->with('success', 'Account created! Please login.');
}
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}