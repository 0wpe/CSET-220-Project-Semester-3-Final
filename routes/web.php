
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

        Route::put('/profile', [EmployeeController::class, 'updateProfile'])
            ->name('employee.profile.update');

        Route::get('/time-entry', [EmployeeController::class, 'timeEntry'])
            ->name('employee.time-entry');
        Route::post('/clock-in', [EmployeeController::class, 'clockIn'])
            ->name('employee.clock-in');

        Route::post('/clock-out', [EmployeeController::class, 'clockOut'])
            ->name('employee.clock-out');

        Route::post('/start-break', [EmployeeController::class, 'startBreak'])
            ->name('employee.start-break');

        Route::post('/resume-work', [EmployeeController::class, 'resumeWork'])
            ->name('employee.resume-work');

        Route::post('/time-entry/clock-in', [EmployeeController::class, 'clockIn'])
            ->name('employee.time-entry.clock-in');

        Route::post('/time-entry/clock-out', [EmployeeController::class, 'clockOut'])
            ->name('employee.time-entry.clock-out');

        // Break Management
        Route::post('/time-entry/start-break', [EmployeeController::class, 'startBreak'])
            ->name('employee.time-entry.start-break');

        Route::post('/time-entry/end-break', [EmployeeController::class, 'endBreak'])
            ->name('employee.time-entry.end-break');

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

        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])
            ->name('admin.leave-requests.index');

        Route::patch('/leave-requests/{leaveRequest}/status', [LeaveRequestController::class, 'update'])
            ->name('admin.leave-requests.update-status');

        // Admin's Own Profile
        Route::get('/profile', [AdminController::class, 'profile'])
            ->name('admin.profile');

        Route::put('/profile', [AdminController::class, 'updateProfile'])
            ->name('admin.profile.update');

        // Individual Employee Profile
        Route::get('/profile-view/{id}', [AdminController::class, 'profileView'])
            ->name('admin.profile-view');

        Route::get('/attendance', [AdminController::class, 'attendance']);

        Route::get('/payroll', [AdminController::class, 'payroll']);

        // Schedule Management
        Route::get('/schedule-management', [AdminController::class, 'scheduleManagement']);

        Route::post('/schedule-management', [AdminController::class, 'storeSchedule'])
            ->name('admin.schedules.store');

        Route::post('/schedules/{schedule}/shifts', [AdminController::class, 'storeShift'])
            ->name('admin.schedules.shifts.store');

        Route::put('/shifts/{shift}', [AdminController::class, 'updateShift'])
            ->name('admin.schedules.shifts.update');

        Route::delete('/shifts/{shift}', [AdminController::class, 'destroyShift'])
            ->name('admin.schedules.shifts.destroy');

        Route::post('/shifts/{shift}/assignments', [AdminController::class, 'storeShiftAssignment'])
            ->name('admin.schedules.assignments.store');

        Route::delete('/shift-assignments/{assignment}', [AdminController::class, 'destroyShiftAssignment'])
            ->name('admin.schedules.assignments.destroy');

        Route::patch('/schedules/{schedule}/draft', [AdminController::class, 'saveScheduleDraft'])
            ->name('admin.schedules.draft');

        Route::patch('/schedules/{schedule}/publish', [AdminController::class, 'publishSchedule'])
            ->name('admin.schedules.publish');

        Route::patch('/schedules/{schedule}/reopen', [AdminController::class, 'reopenSchedule'])
            ->name('admin.schedules.reopen');

        Route::get('/statistics', [AdminController::class, 'statistics']);
    });
