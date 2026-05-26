<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Loan;
use App\Models\OfficialDocument;
use App\Models\Payroll;
use Carbon\Carbon;

class DashboardService
{
    public function summary(): array
    {
        $now = now();
        $month = (int) $now->month;
        $year = (int) $now->year;

        $payrollTotal = Payroll::where('period_month', $month)->where('period_year', $year)->sum('net_salary');
        $employees = Employee::count();
        $active = Employee::where('status', 'active')->count();
        $absences = Attendance::whereMonth('date', $month)->whereYear('date', $year)->where('status', 'absent')->count();
        $expiringDocs = OfficialDocument::whereDate('expires_on', '<=', $now->copy()->addDays(45))->whereDate('expires_on', '>=', $now)->count();

        return [
            'employees' => $employees,
            'active_employees' => $active,
            'monthly_payroll' => (float) $payrollTotal,
            'open_loans' => (float) Loan::where('status', 'active')->sum('amount') - (float) Loan::where('status', 'active')->sum('paid_amount'),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'expiring_documents' => $expiringDocs,
            'monthly_absences' => $absences,
            'absence_rate' => $active > 0 ? round(($absences / max(1, $active * Carbon::now()->daysInMonth)) * 100, 2) : 0,
        ];
    }
}
