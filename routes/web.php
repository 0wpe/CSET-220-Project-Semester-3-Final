<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveRequestController;

// Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Employee Routes
Route::middleware(['auth', 'role:Employee'])
    ->prefix('employee')
    ->group(function () {

        Route::get('/dashboard', [EmployeeController::class, 'dashboard']);

        Route::get('/profile', [EmployeeController::class, 'profile']);

        Route::get('/time-entry', [EmployeeController::class, 'timeEntry']);

        Route::get('/my-schedule', [EmployeeController::class, 'mySchedule']);

        Route::get('/payslips', [EmployeeController::class, 'payslips'])
            ->name('employee.payslips');

        Route::get('/payslips/{payrollId}/download', [PayrollController::class, 'downloadPayslip'])
            ->name('employee.payslips.download');

        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])
            ->name('employee.leave-requests.index');

        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])
            ->name('leave-requests.store');
    });

// Authenticated Admin Routes
Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        Route::get('/employees', [AdminController::class, 'employees']);

        Route::post('/employees', [AdminController::class, 'store']);

        Route::put('/employees/{id}', [AdminController::class, 'update']);

        Route::delete('/employees/{id}', [AdminController::class, 'destroy']);

        Route::get('/profile-view', [AdminController::class, 'profileView']);

        Route::get('/attendance', [AdminController::class, 'attendance']);

        Route::get('/payroll', [AdminController::class, 'payroll']);

        Route::get('/schedule-management', [AdminController::class, 'scheduleManagement']);

        Route::get('/statistics', [AdminController::class, 'statistics']);
    });
