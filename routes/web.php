<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;

// Login
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);

// Employee pages
Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard']);
Route::get('/employee/profile', [EmployeeController::class, 'profile']);
Route::get('/employee/time-entry', [EmployeeController::class, 'timeEntry']);
Route::get('/employee/leave-requests', [EmployeeController::class, 'leaveRequests']);
Route::get('/employee/my-schedule', [EmployeeController::class, 'mySchedule']);
Route::get('/employee/payslips', [EmployeeController::class, 'payslips']);

// Admin pages
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/admin/employees', [AdminController::class, 'employees']);
Route::get('/admin/profile-view', [AdminController::class, 'profileView']);
Route::get('/admin/attendance', [AdminController::class, 'attendance']);
Route::get('/admin/payroll', [AdminController::class, 'payroll']);
Route::get('/admin/schedule-management', [AdminController::class, 'scheduleManagement']);
Route::get('/admin/statistics', [AdminController::class, 'statistics']);