<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', fn() => redirect('/login'));

// ── Auth ───────────────────────────────────────────────────────
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ── Dashboard redirect (role-based) ───────────────────────────
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('resident.complaints.create');
})->middleware('auth');

// ── Admin routes ───────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/complaints',             [AdminController::class, 'complaints'])->name('admin.complaints');
    Route::get('/complaints/{id}/resolve',[AdminController::class, 'resolve'])->name('admin.resolve');
    Route::put('/complaints/{id}',        [AdminController::class, 'updateComplaint'])->name('admin.complaints.update');
    Route::get('/citizens',               [AdminController::class, 'citizens'])->name('admin.citizens');
    Route::post('/citizens',              [AdminController::class, 'storeCitizen'])->name('admin.citizens.store');
    Route::get('/reports',                [AdminController::class, 'reports'])->name('admin.reports');
});

// ── Resident routes ────────────────────────────────────────────
Route::middleware(['auth'])->prefix('resident')->group(function () {
    Route::get('/complaints/create', [ResidentController::class, 'showFileComplaint'])->name('resident.complaints.create');
    Route::post('/complaints',       [ResidentController::class, 'storeComplaint'])->name('resident.complaints.store');
    Route::get('/complaints',        [ResidentController::class, 'myComplaints'])->name('resident.complaints.index');
});