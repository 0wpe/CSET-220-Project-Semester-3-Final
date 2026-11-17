<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;

// Login Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Employee pages (Protected by auth and Employee role)
Route::middleware(['auth', 'role:Employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard']);
    Route::get('/profile', [EmployeeController::class, 'profile']);
    Route::get('/time-entry', [EmployeeController::class, 'timeEntry']);
    Route::get('/leave-requests', [EmployeeController::class, 'leaveRequests']);
    Route::get('/my-schedule', [EmployeeController::class, 'mySchedule']);
    Route::get('/payslips', [EmployeeController::class, 'payslips']);
});

// Admin pages (Protected by auth and Admin role)
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/employees', [AdminController::class, 'employees']);
    Route::get('/profile-view', [AdminController::class, 'profileView']);
    Route::get('/attendance', [AdminController::class, 'attendance']);
    Route::get('/payroll', [AdminController::class, 'payroll']);
    Route::get('/schedule-management', [AdminController::class, 'scheduleManagement']);
    Route::get('/statistics', [AdminController::class, 'statistics']);
});
