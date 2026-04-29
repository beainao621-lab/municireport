<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ComplaintMessageController;
use App\Http\Controllers\ComplaintCommentController;

Route::get('/', fn() => redirect('/login'));

// ── Auth ───────────────────────────────────────────────────────
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ── Dashboard redirect ─────────────────────────────────────────
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('resident.complaints.create');
})->middleware('auth');

// ── Shared authenticated routes ────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::match(['PUT', 'POST'], '/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::post('/profile/verify-password', [ProfileController::class, 'verifyPassword'])
        ->name('profile.verify-password');

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/',                [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read',      [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/mark-all-read',  [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
        Route::post('/delete-selected',[NotificationController::class, 'deleteSelected'])->name('notifications.deleteSelected');
        Route::delete('/{id}',         [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Messages (must be before admin/resident prefixes to avoid conflicts)
    Route::prefix('messages')->group(function () {
        Route::get('/admin/unread-counts', [ComplaintMessageController::class, 'adminUnreadCounts'])->name('messages.admin.unread');
        Route::get('/resident/unread',     [ComplaintMessageController::class, 'residentUnreadCount'])->name('messages.resident.unread');
        Route::get('/{complaintId}',       [ComplaintMessageController::class, 'index'])->name('messages.index');
        Route::post('/{complaintId}',      [ComplaintMessageController::class, 'store'])->name('messages.store');
    });

    // Complaint Comments (resident posts, admin views)
    Route::prefix('complaint-comments')->group(function () {
        Route::get('/{complaintId}',   [ComplaintCommentController::class, 'index'])->name('complaint.comments.index');
        Route::post('/{complaintId}',  [ComplaintCommentController::class, 'store'])->name('complaint.comments.store');
    });

    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard',           [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/complaints',          [AdminController::class, 'complaints'])->name('admin.complaints');
        Route::get('/resolved',            [AdminController::class, 'resolved'])->name('admin.resolved');
        Route::post('/complaints/{id}',    [AdminController::class, 'updateComplaint'])->name('admin.complaints.update');
        Route::delete('/complaints/{id}',  [AdminController::class, 'deleteComplaint'])->name('admin.complaints.delete');
        Route::get('/citizens',            [AdminController::class, 'citizens'])->name('admin.citizens');
        Route::post('/citizens',           [AdminController::class, 'storeCitizen'])->name('admin.citizens.store');
        Route::post('/citizens/{id}',      [AdminController::class, 'updateCitizen'])->name('admin.citizens.update');
        Route::get('/reports',             [AdminController::class, 'reports'])->name('admin.reports');
    });

    // Resident
    Route::prefix('resident')->group(function () {
        Route::get('/complaints/create',    [ResidentController::class, 'showFileComplaint'])->name('resident.complaints.create');
        Route::post('/complaints',          [ResidentController::class, 'storeComplaint'])->name('resident.complaints.store');
        Route::get('/complaints',           [ResidentController::class, 'myComplaints'])->name('resident.complaints.index');
        Route::delete('/complaints/{id}',   [ResidentController::class, 'deleteComplaint'])->name('resident.complaints.delete');
        Route::get('/messages',             [ResidentController::class, 'messages'])->name('resident.messages');
    });
});