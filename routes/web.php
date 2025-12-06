<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route:: get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Lecturer quota management
        Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('lecturers');
        Route::get('/lecturers/{id}/quota', [AdminController::class, 'editLecturerQuota'])->name('lecturers.quota');
        Route::put('/lecturers/{id}/quota', [AdminController::class, 'updateLecturerQuota'])->name('lecturers.quota.update');
        
        // Submissions monitoring
        Route::get('/submissions', [AdminController::class, 'submissions'])->name('submissions');
    });

    // Dosen routes
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [DosenController::class, 'profile'])->name('profile');
        Route::put('/profile', [DosenController::class, 'updateProfile'])->name('profile.update');
        Route::get('/requests', [DosenController::class, 'requests'])->name('requests');
        Route::post('/requests/{id}/approve', [DosenController::class, 'approveSubmission'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [DosenController::class, 'rejectSubmission'])->name('requests.reject');
        Route::get('/students', [DosenController::class, 'students'])->name('students');
        Route::get('/notifications', [DosenController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [DosenController::class, 'markNotificationAsRead'])->name('notifications.read');
    });

    // Mahasiswa routes
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/browse-lecturers', [MahasiswaController::class, 'browseLecturers'])->name('browse');
        Route::post('/submit', [MahasiswaController::class, 'submitRequest'])->name('submit');
        Route::get('/submissions', [MahasiswaController::class, 'submissions'])->name('submissions');
        Route::get('/notifications', [MahasiswaController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [MahasiswaController::class, 'markNotificationAsRead'])->name('notifications.read');
    });
});
