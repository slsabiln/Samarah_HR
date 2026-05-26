<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Loan;
use App\Models\OfficialDocument;
use App\Models\Payroll;
use App\Models\Penalty;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __invoke(): View
    {
        $month = now()->month;
        $year = now()->year;
        $activeEmployees = max(1, Employee::where('status', 'active')->count());
        $absences = Attendance::whereMonth('date', $month)->whereYear('date', $year)->where('status', 'absent')->count();

        return view('reports.index', [
            'cards' => [
                ['title' => 'إجمالي الرواتب الصافية لهذا الشهر', 'value' => money_kwd(Payroll::where('period_month', $month)->where('period_year', $year)->sum('net_salary'))],
                ['title' => 'رصيد القروض المفتوحة', 'value' => money_kwd((float) Loan::where('status', 'active')->sum('amount') - (float) Loan::where('status', 'active')->sum('paid_amount'))],
                ['title' => 'الإجازات المعتمدة هذا الشهر', 'value' => LeaveRequest::whereMonth('start_date', $month)->whereYear('start_date', $year)->where('status', 'approved')->count()],
                ['title' => 'نسبة الغياب التقريبية', 'value' => round(($absences / ($activeEmployees * now()->daysInMonth)) * 100, 2).'%'],
                ['title' => 'الوثائق القريبة من الانتهاء', 'value' => OfficialDocument::whereDate('expires_on', '<=', now()->addDays(45))->whereDate('expires_on', '>=', now())->count()],
                ['title' => 'خصومات الجزاءات هذا الشهر', 'value' => money_kwd(Penalty::whereMonth('date', $month)->whereYear('date', $year)->sum('deduction_amount'))],
            ],
        ]);
    }
}
