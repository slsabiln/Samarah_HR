<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(Request $request): View
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        return view('payrolls.index', [
            'month' => $month,
            'year' => $year,
            'payrolls' => Payroll::with('employee')
                ->where('period_month', $month)
                ->where('period_year', $year)
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString(),
            'totalNet' => Payroll::where('period_month', $month)->where('period_year', $year)->sum('net_salary'),
        ]);
    }

    public function generate(Request $request, PayrollService $service): RedirectResponse
    {
        $data = $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2020,2100'],
        ]);

        $service->generate((int) $data['month'], (int) $data['year']);

        return redirect()->route('payrolls.index', $data)->with('success', 'تم توليد مسير الرواتب للشهر المحدد.');
    }

    public function pay(Payroll $payroll, PayrollService $service): RedirectResponse
    {
        $service->markPaid($payroll);
        return back()->with('success', 'تم اعتماد الراتب كمدفوع وتحديث الأقساط.');
    }
}
