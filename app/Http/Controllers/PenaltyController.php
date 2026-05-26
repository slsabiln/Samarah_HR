<?php

namespace App\Http\Controllers;

use App\Models\Penalty;

class PenaltyController extends SimpleResourceController
{
    protected string $model = Penalty::class;
    protected string $routeName = 'penalties';
    protected string $title = 'الجزاءات والتقويم الوظيفي';
    protected string $subtitle = 'تسجيل الجزاءات والنقاط وربطها بالخصومات المالية.';
    protected array $with = ['employee'];

    protected array $columns = [
        ['key' => 'employee.name', 'label' => 'الموظف'],
        ['key' => 'title', 'label' => 'الجزاء'],
        ['key' => 'date', 'label' => 'التاريخ', 'format' => 'date'],
        ['key' => 'points', 'label' => 'النقاط'],
        ['key' => 'deduction_amount', 'label' => 'الخصم', 'format' => 'money'],
    ];

    protected array $fields = [
        ['name' => 'employee_id', 'label' => 'الموظف', 'type' => 'employee_select', 'rules' => ['required', 'exists:employees,id']],
        ['name' => 'title', 'label' => 'عنوان الجزاء', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'date', 'label' => 'التاريخ', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'points', 'label' => 'النقاط المخصومة', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
        ['name' => 'deduction_amount', 'label' => 'مبلغ الخصم', 'type' => 'money', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];
}
