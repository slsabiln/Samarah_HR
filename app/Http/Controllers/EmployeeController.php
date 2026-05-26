<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class EmployeeController extends SimpleResourceController
{
    protected string $model = Employee::class;
    protected string $routeName = 'employees';
    protected string $title = 'إدارة الموظفين';
    protected string $subtitle = 'قاعدة بيانات مركزية للموظفين مع بيانات الراتب والحالة الوظيفية.';
    protected string $orderBy = 'name';
    protected string $orderDirection = 'asc';

    protected array $columns = [
        ['key' => 'code', 'label' => 'الكود'],
        ['key' => 'name', 'label' => 'اسم الموظف'],
        ['key' => 'job_title', 'label' => 'المسمى'],
        ['key' => 'department', 'label' => 'القسم'],
        ['key' => 'basic_salary', 'label' => 'الراتب', 'format' => 'money'],
        ['key' => 'status', 'label' => 'الحالة', 'format' => 'status'],
    ];

    protected array $fields = [
        ['name' => 'code', 'label' => 'كود الموظف', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:50']],
        ['name' => 'name', 'label' => 'اسم الموظف', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'job_title', 'label' => 'المسمى الوظيفي', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        ['name' => 'department', 'label' => 'القسم', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        ['name' => 'phone', 'label' => 'الهاتف', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:50']],
        ['name' => 'email', 'label' => 'البريد', 'type' => 'email', 'rules' => ['nullable', 'email', 'max:255']],
        ['name' => 'hire_date', 'label' => 'تاريخ الالتحاق', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'basic_salary', 'label' => 'الراتب الأساسي', 'type' => 'money', 'rules' => ['required', 'numeric', 'min:0']],
        ['name' => 'allowances', 'label' => 'البدلات الشهرية', 'type' => 'money', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'status', 'label' => 'الحالة', 'type' => 'select', 'options' => ['active' => 'نشط', 'on_leave' => 'في إجازة', 'inactive' => 'غير نشط'], 'rules' => ['required', 'in:active,on_leave,inactive']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];
}
