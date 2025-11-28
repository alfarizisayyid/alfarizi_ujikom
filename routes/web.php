<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\AnnouncementController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// User Routes
Route::middleware(['auth', 'auth.user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    Route::get('/registration', [RegistrationController::class, 'create'])->name('user.registration.create');
    Route::post('/registration', [RegistrationController::class, 'store'])->name('user.registration.store');
    
    Route::get('/registration/documents', [RegistrationController::class, 'documentsForm'])->name('user.registration.documents');
    Route::post('/registration/documents/upload', [RegistrationController::class, 'uploadDocument'])->name('user.registration.upload');
    Route::delete('/registration/documents/{document}', [RegistrationController::class, 'deleteDocument'])->name('user.registration.delete');
    
    Route::post('/registration/submit', [RegistrationController::class, 'submit'])->name('user.registration.submit');
    Route::get('/registration/status', [RegistrationController::class, 'status'])->name('user.registration.status');
});

// Admin Routes
Route::middleware(['auth', 'auth.admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Registrations Management
    Route::get('/admin/registrations', [AdminRegistrationController::class, 'index'])->name('admin.registrations.index');
    Route::get('/admin/registrations/{registration}', [AdminRegistrationController::class, 'show'])->name('admin.registrations.show');
    Route::get('/admin/registrations/{registration}/verify', [AdminRegistrationController::class, 'verify'])->name('admin.registrations.verify');
    Route::post('/admin/registrations/{registration}/accept', [AdminRegistrationController::class, 'accept'])->name('admin.registrations.accept');
    Route::get('/admin/registrations/{registration}/accept', function ($registration) {
        return redirect()->route('admin.registrations.verify', $registration);
    });
    Route::post('/admin/registrations/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('admin.registrations.reject');
    Route::post('/admin/documents/status', [AdminRegistrationController::class, 'updateDocumentStatus'])->name('admin.documents.status');
    Route::get('/admin/documents/{document}/download', [AdminRegistrationController::class, 'downloadDocument'])->name('admin.documents.download');
    
    // Schedules Management
    Route::get('/admin/schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::get('/admin/schedules/create', [ScheduleController::class, 'create'])->name('admin.schedules.create');
    Route::post('/admin/schedules', [ScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::get('/admin/schedules/{schedule}', [ScheduleController::class, 'show'])->name('admin.schedules.show');
    Route::get('/admin/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('admin.schedules.edit');
    Route::put('/admin/schedules/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::post('/admin/schedules/{schedule}/participants', [ScheduleController::class, 'addParticipants'])->name('admin.schedules.add-participants');
    Route::put('/admin/schedules/participants/{participant}', [ScheduleController::class, 'updateParticipantStatus'])->name('admin.schedules.update-participant');
    Route::delete('/admin/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
    
    // Announcements Management
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/admin/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('admin.announcements.show');
    Route::get('/admin/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/admin/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
});
