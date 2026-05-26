<?php

namespace App\Http\Controllers;

use App\Models\Training;

class TrainingController extends SimpleResourceController
{
    protected string $model = Training::class;
    protected string $routeName = 'trainings';
    protected string $title = 'التدريب والتطوير';
    protected string $subtitle = 'إدارة خطط التدريب وتقييم الكفاءات وأثر التدريب على الأداء.';
    protected array $with = ['employee'];

    protected array $columns = [
        ['key' => 'employee.name', 'label' => 'الموظف'],
        ['key' => 'title', 'label' => 'البرنامج'],
        ['key' => 'provider', 'label' => 'الجهة'],
        ['key' => 'start_date', 'label' => 'البداية', 'format' => 'date'],
        ['key' => 'score', 'label' => 'التقييم'],
    ];

    protected array $fields = [
        ['name' => 'employee_id', 'label' => 'الموظف', 'type' => 'employee_select', 'rules' => ['required', 'exists:employees,id']],
        ['name' => 'title', 'label' => 'اسم البرنامج', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'provider', 'label' => 'جهة التدريب', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        ['name' => 'start_date', 'label' => 'تاريخ البداية', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'end_date', 'label' => 'تاريخ النهاية', 'type' => 'date', 'rules' => ['nullable', 'date', 'after_or_equal:start_date']],
        ['name' => 'score', 'label' => 'تقييم الكفاءة %', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0', 'max:100']],
        ['name' => 'impact', 'label' => 'أثر التدريب', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];
}
