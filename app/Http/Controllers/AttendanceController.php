<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AttendanceController extends SimpleResourceController
{
    protected string $model = Attendance::class;
    protected string $routeName = 'attendances';
    protected string $title = 'الحضور والانصراف';
    protected string $subtitle = 'ربط ساعات العمل الفعلية بالرواتب والإجازات وتقارير الغياب والتأخير.';
    protected array $with = ['employee'];
    protected string $orderBy = 'date';
    protected string $orderDirection = 'desc';

    protected array $columns = [
        ['key' => 'employee.name', 'label' => 'الموظف'],
        ['key' => 'date', 'label' => 'التاريخ', 'format' => 'date'],
        ['key' => 'check_in', 'label' => 'دخول'],
        ['key' => 'check_out', 'label' => 'خروج'],
        ['key' => 'worked_hours', 'label' => 'ساعات العمل'],
        ['key' => 'overtime_hours', 'label' => 'إضافي'],
        ['key' => 'status', 'label' => 'الحالة', 'format' => 'status'],
    ];

    protected array $fields = [
        ['name' => 'employee_id', 'label' => 'الموظف', 'type' => 'employee_select', 'rules' => ['required', 'exists:employees,id']],
        ['name' => 'date', 'label' => 'التاريخ', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'check_in', 'label' => 'وقت الدخول', 'type' => 'time', 'rules' => ['nullable', 'date_format:H:i']],
        ['name' => 'check_out', 'label' => 'وقت الخروج', 'type' => 'time', 'rules' => ['nullable', 'date_format:H:i']],
        ['name' => 'worked_hours', 'label' => 'ساعات العمل', 'type' => 'number', 'step' => '0.25', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'overtime_hours', 'label' => 'ساعات الإضافي', 'type' => 'number', 'step' => '0.25', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'status', 'label' => 'الحالة', 'type' => 'select', 'options' => ['present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب', 'day_off' => 'يوم راحة'], 'rules' => ['required', 'in:present,late,absent,day_off']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];

    protected function mutateData(array $data, ?Model $record): array
    {
        if (($data['check_in'] ?? null) && ($data['check_out'] ?? null) && empty($data['worked_hours'])) {
            $in = Carbon::createFromFormat('H:i', $data['check_in']);
            $out = Carbon::createFromFormat('H:i', $data['check_out']);
            $data['worked_hours'] = round($in->diffInMinutes($out) / 60, 2);
        }
        return $data;
    }
}
