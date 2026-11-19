<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PayrollController;

// Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Employee Routes
Route::middleware(['auth', 'role:Employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard']);
    Route::get('/profile', [EmployeeController::class, 'profile']);
    Route::get('/time-entry', [EmployeeController::class, 'timeEntry']);
    Route::get('/leave-requests', [EmployeeController::class, 'leaveRequests']);
    Route::get('/my-schedule', [EmployeeController::class, 'mySchedule']);

    Route::get('/payslips', [EmployeeController::class, 'payslips'])
        ->name('employee.payslips');

    Route::get('/payslips/{payrollId}/download', [PayrollController::class, 'downloadPayslip'])
        ->name('employee.payslips.download');
});

// Admin pages
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

Route::get('/admin/employees', [AdminController::class, 'employees']);
Route::post('/admin/employees', [AdminController::class, 'store']);
Route::put('/admin/employees/{id}', [AdminController::class, 'update']);
Route::delete('/admin/employees/{id}', [AdminController::class, 'destroy']);

Route::get('/admin/profile-view', [AdminController::class, 'profileView']);
Route::get('/admin/attendance', [AdminController::class, 'attendance']);
Route::get('/admin/payroll', [AdminController::class, 'payroll']);
Route::get('/admin/schedule-management', [AdminController::class, 'scheduleManagement']);
Route::get('/admin/statistics', [AdminController::class, 'statistics']);
