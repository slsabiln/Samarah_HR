<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficialDocument;
use App\Models\Payroll;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboard): View
    {
        return view('dashboard', [
            'summary' => $dashboard->summary(),
            'latestPayrolls' => Payroll::with('employee')->latest()->limit(6)->get(),
            'expiringDocuments' => OfficialDocument::with('employee')
                ->whereDate('expires_on', '<=', now()->addDays(45))
                ->orderBy('expires_on')
                ->limit(6)
                ->get(),
            'recentAttendance' => Attendance::with('employee')->latest('date')->limit(8)->get(),
        ]);
    }
}
