<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestController extends SimpleResourceController
{
    protected string $model = LeaveRequest::class;
    protected string $routeName = 'leaves';
    protected string $title = 'الإجازات';
    protected string $subtitle = 'تسجيل الإجازات السنوية، المرضية، الحج، الوضع، وبدون أجر.';
    protected array $with = ['employee'];

    protected array $columns = [
        ['key' => 'employee.name', 'label' => 'الموظف'],
        ['key' => 'type', 'label' => 'النوع'],
        ['key' => 'start_date', 'label' => 'من', 'format' => 'date'],
        ['key' => 'end_date', 'label' => 'إلى', 'format' => 'date'],
        ['key' => 'days', 'label' => 'الأيام'],
        ['key' => 'status', 'label' => 'الحالة', 'format' => 'status'],
    ];

    protected array $fields = [
        ['name' => 'employee_id', 'label' => 'الموظف', 'type' => 'employee_select', 'rules' => ['required', 'exists:employees,id']],
        ['name' => 'type', 'label' => 'نوع الإجازة', 'type' => 'select', 'options' => ['annual' => 'سنوية', 'unpaid' => 'بدون أجر', 'sick' => 'مرضية', 'hajj' => 'حج', 'maternity' => 'وضع', 'other' => 'أخرى'], 'rules' => ['required', 'string']],
        ['name' => 'start_date', 'label' => 'تاريخ البداية', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'end_date', 'label' => 'تاريخ النهاية', 'type' => 'date', 'rules' => ['required', 'date', 'after_or_equal:start_date']],
        ['name' => 'paid', 'label' => 'مدفوعة الأجر', 'type' => 'select', 'options' => ['1' => 'نعم', '0' => 'لا'], 'rules' => ['required', 'boolean']],
        ['name' => 'status', 'label' => 'الحالة', 'type' => 'select', 'options' => ['pending' => 'قيد المراجعة', 'approved' => 'معتمد', 'rejected' => 'مرفوض'], 'rules' => ['required', 'in:pending,approved,rejected']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];

    protected function mutateData(array $data, ?Model $record): array
    {
        $data['paid'] = (bool) $data['paid'];
        $data['days'] = Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;
        return $data;
    }
}
