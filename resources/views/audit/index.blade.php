@extends('layouts.app', ['pageTitle' => 'سجل التدقيق'])

@section('content')
<section class="page-head">
    <div>
        <h2>سجل التدقيق Audit Trail</h2>
        <p>متابعة التغييرات التي تتم على السجلات الإدارية والمالية.</p>
    </div>
</section>

<section class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>الوقت</th><th>المستخدم</th><th>الإجراء</th><th>النوع</th><th>رقم السجل</th><th>IP</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                    <td>{{ $log->user?->name ?: '-' }}</td>
                    <td><span class="badge">{{ $log->action }}</span></td>
                    <td>{{ class_basename($log->auditable_type) }}</td>
                    <td>{{ $log->auditable_id }}</td>
                    <td>{{ $log->ip ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">لا توجد عمليات مسجلة بعد.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</section>
@endsection
