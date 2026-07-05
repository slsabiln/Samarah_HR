<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveBalanceAdjustmentController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\OfficialDocumentController;
use App\Http\Controllers\OfficialHolidayController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/employees/import', [EmployeeController::class, 'importForm'])
        ->name('employees.import.form');
    Route::post('/employees/import', [EmployeeController::class, 'import'])
        ->name('employees.import.store');

    Route::get('/employees/excel-export', [EmployeeController::class, 'exportExcel'])
        ->name('employees.excel-export');

    Route::get('/leaves/excel-export', [LeaveRequestController::class, 'exportExcel'])
        ->name('leaves.excel-export');

    Route::get('/official-holidays/excel-export', [OfficialHolidayController::class, 'exportExcel'])
        ->name('official-holidays.excel-export');

    Route::get('/leave-balance-adjustments/excel-export', [LeaveBalanceAdjustmentController::class, 'exportExcel'])
        ->name('leave-balance-adjustments.excel-export');

    Route::get('/loans/excel-export', [LoanController::class, 'exportExcel'])
        ->name('loans.excel-export');

    Route::get('/documents/excel-export', [OfficialDocumentController::class, 'exportExcel'])
        ->name('documents.excel-export');

    Route::get('/penalties/excel-export', [PenaltyController::class, 'exportExcel'])
        ->name('penalties.excel-export');

    Route::get('/attendances/excel-export', [AttendanceController::class, 'exportExcel'])
        ->name('attendances.excel-export');

    Route::get('/trainings/excel-export', [TrainingController::class, 'exportExcel'])
        ->name('trainings.excel-export');

    Route::resource('employees', EmployeeController::class)->except('show');
    Route::resource('leaves', LeaveRequestController::class)->except('show');
    Route::resource('official-holidays', OfficialHolidayController::class)->except('show');
    Route::resource('leave-balance-adjustments', LeaveBalanceAdjustmentController::class)->except('show');
    Route::resource('loans', LoanController::class)->except('show');
    Route::resource('documents', OfficialDocumentController::class)->except('show');
    Route::resource('penalties', PenaltyController::class)->except('show');
    Route::resource('attendances', AttendanceController::class)->except('show');
    Route::resource('trainings', TrainingController::class)->except('show');

    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
    Route::patch('/payrolls/{payroll}/pay', [PayrollController::class, 'pay'])->name('payrolls.pay');

    Route::get('/reports', ReportController::class)->name('reports');
    Route::get('/audit-logs', AuditLogController::class)->name('audit.index');
});