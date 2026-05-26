@extends('layouts.app', ['pageTitle' => 'الرواتب الشهرية'])

@section('content')
<section class="page-head">
    <div>
        <h2>الرواتب الشهرية</h2>
        <p>توليد كشف الراتب: الأساسي + البدلات + الإضافي - الإجازات غير المدفوعة - الجزاءات - القروض.</p>
    </div>
</section>

<section class="panel">
    <form class="toolbar" method="POST" action="{{ route('payrolls.generate') }}">
        @csrf
        <label><span>الشهر</span><input type="number" name="month" min="1" max="12" value="{{ $month }}"></label>
        <label><span>السنة</span><input type="number" name="year" min="2020" max="2100" value="{{ $year }}"></label>
        <button class="btn btn-primary" type="submit">توليد/تحديث المسير</button>
    </form>
    <div class="summary-line">إجمالي صافي الرواتب: <strong>{{ money_kwd($totalNet) }}</strong></div>
</section>

<section class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>الموظف</th><th>الأساسي</th><th>البدلات</th><th>الإضافي</th><th>خصم الإجازات</th><th>الجزاءات</th><th>القروض</th><th>الصافي</th><th>الحالة</th><th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee?->name }}</td>
                        <td>{{ money_kwd($payroll->basic_salary) }}</td>
                        <td>{{ money_kwd($payroll->allowances) }}</td>
                        <td>{{ money_kwd($payroll->overtime_amount) }}</td>
                        <td>{{ money_kwd($payroll->leave_deduction) }}</td>
                        <td>{{ money_kwd($payroll->penalty_deduction) }}</td>
                        <td>{{ money_kwd($payroll->loan_deduction) }}</td>
                        <td><strong>{{ money_kwd($payroll->net_salary) }}</strong></td>
                        <td><span class="badge">{{ status_badge($payroll->status) }}</span></td>
                        <td>
                            @if($payroll->status !== 'paid')
                                <form method="POST" action="{{ route('payrolls.pay', $payroll) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-primary" type="submit">اعتماد الدفع</button>
                                </form>
                            @else
                                <small>{{ optional($payroll->paid_at)->format('Y-m-d') }}</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="empty">لم يتم توليد رواتب لهذا الشهر بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payrolls->links() }}
</section>
@endsection
