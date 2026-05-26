@extends('layouts.app', ['pageTitle' => 'لوحة التحكم'])

@section('content')
<section class="hero-card">
    <div>
        <p class="eyebrow">نظام موارد بشرية فعلي</p>
        <h2>ابدأ بإدخال بيانات الشركة والموظفين</h2>
        <p>النظام يبدأ بدون بيانات تجريبية، وكل الأرقام المعروضة يتم احتسابها من بياناتك الفعلية فقط.</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-on-dark">إضافة أول موظف</a>
</section>

<section class="stats-grid">
    <x-stat-card title="الموظفون" :value="$summary['employees']" hint="إجمالي السجلات" />
    <x-stat-card title="النشطون" :value="$summary['active_employees']" hint="حالة نشطة" />
    <x-stat-card title="رواتب الشهر" :value="money_kwd($summary['monthly_payroll'])" hint="صافي الرواتب" />
    <x-stat-card title="قروض مفتوحة" :value="money_kwd($summary['open_loans'])" hint="المتبقي" />
    <x-stat-card title="إجازات معلقة" :value="$summary['pending_leaves']" hint="تحتاج اعتماد" />
    <x-stat-card title="وثائق قرب الانتهاء" :value="$summary['expiring_documents']" hint="خلال 45 يوم" />
</section>

@if($summary['employees'] == 0)
    <section class="panel empty-state-panel">
        <div class="empty-state-icon">HR</div>
        <h3>لا توجد بيانات موظفين بعد</h3>
        <p>أضف الموظفين الحقيقيين أولاً، وبعدها تستطيع تسجيل الحضور، الإجازات، الوثائق، القروض، الجزاءات، وتوليد الرواتب الشهرية بالدينار الكويتي.</p>
        <div class="empty-actions">
            <a class="btn btn-primary" href="{{ route('employees.create') }}">إضافة موظف</a>
            <a class="btn btn-light" href="{{ route('documents.index') }}">إدارة الوثائق</a>
        </div>
    </section>
@endif

<section class="module-grid">
    <a class="module-card" href="{{ route('employees.index') }}"><span>الموظفون</span><strong>ملفات الموظفين</strong><small>بيانات شخصية ووظيفية ورواتب أساسية</small></a>
    <a class="module-card" href="{{ route('payrolls.index') }}"><span>الرواتب</span><strong>مسير الرواتب</strong><small>أساسي، بدلات، إضافي، خصومات، صافي</small></a>
    <a class="module-card" href="{{ route('attendances.index') }}"><span>الحضور</span><strong>الحضور والانصراف</strong><small>ساعات العمل والتأخير والغياب</small></a>
    <a class="module-card" href="{{ route('documents.index') }}"><span>الوثائق</span><strong>الإقامات والجوازات</strong><small>تواريخ انتهاء وتنبيهات متابعة</small></a>
</section>

<div class="content-grid">
    <section class="panel">
        <div class="panel__head">
            <h3>آخر مسيرات الرواتب</h3>
            <a href="{{ route('payrolls.index') }}">عرض الكل</a>
        </div>
        <div class="mini-list">
            @forelse($latestPayrolls as $payroll)
                <div class="mini-row">
                    <div><strong>{{ $payroll->employee?->name }}</strong><small>{{ $payroll->period_month }}/{{ $payroll->period_year }}</small></div>
                    <span>{{ money_kwd($payroll->net_salary) }}</span>
                </div>
            @empty
                <p class="empty">لم يتم توليد رواتب بعد.</p>
            @endforelse
        </div>
    </section>

    <section class="panel">
        <div class="panel__head">
            <h3>تنبيهات الوثائق</h3>
            <a href="{{ route('documents.index') }}">إدارة الوثائق</a>
        </div>
        <div class="mini-list">
            @forelse($expiringDocuments as $document)
                <div class="mini-row danger-soft">
                    <div><strong>{{ $document->employee?->name }}</strong><small>{{ status_badge($document->type) }} - {{ optional($document->expires_on)->format('Y-m-d') }}</small></div>
                    <span>{{ status_badge($document->status) }}</span>
                </div>
            @empty
                <p class="empty">لا توجد وثائق قريبة الانتهاء.</p>
            @endforelse
        </div>
    </section>
</div>

<section class="panel">
    <div class="panel__head">
        <h3>آخر الحضور والانصراف</h3>
        <a href="{{ route('attendances.index') }}">إدارة الحضور</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>الموظف</th><th>التاريخ</th><th>دخول</th><th>خروج</th><th>الحالة</th></tr></thead>
            <tbody>
            @forelse($recentAttendance as $row)
                <tr><td>{{ $row->employee?->name }}</td><td>{{ optional($row->date)->format('Y-m-d') }}</td><td>{{ $row->check_in ?: '-' }}</td><td>{{ $row->check_out ?: '-' }}</td><td><span class="badge">{{ status_badge($row->status) }}</span></td></tr>
            @empty
                <tr><td colspan="5" class="empty">لا توجد بيانات حضور بعد.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
