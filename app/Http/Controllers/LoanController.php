<?php

namespace App\Http\Controllers;

use App\Models\Loan;

class LoanController extends SimpleResourceController
{
    protected string $model = Loan::class;
    protected string $routeName = 'loans';
    protected string $title = 'القروض والسلف والمديونيات';
    protected string $subtitle = 'خطة سداد شهرية تلقائية وخصم الأقساط من كشف الراتب.';
    protected array $with = ['employee'];

    protected array $columns = [
        ['key' => 'employee.name', 'label' => 'الموظف'],
        ['key' => 'type', 'label' => 'النوع'],
        ['key' => 'amount', 'label' => 'المبلغ', 'format' => 'money'],
        ['key' => 'monthly_installment', 'label' => 'القسط', 'format' => 'money'],
        ['key' => 'paid_amount', 'label' => 'المدفوع', 'format' => 'money'],
        ['key' => 'status', 'label' => 'الحالة', 'format' => 'status'],
    ];

    protected array $fields = [
        ['name' => 'employee_id', 'label' => 'الموظف', 'type' => 'employee_select', 'rules' => ['required', 'exists:employees,id']],
        ['name' => 'type', 'label' => 'النوع', 'type' => 'select', 'options' => ['loan' => 'قرض', 'advance' => 'سلفة', 'debt' => 'مديونية'], 'rules' => ['required', 'string']],
        ['name' => 'amount', 'label' => 'إجمالي المبلغ', 'type' => 'money', 'rules' => ['required', 'numeric', 'min:0.001']],
        ['name' => 'monthly_installment', 'label' => 'القسط الشهري', 'type' => 'money', 'rules' => ['required', 'numeric', 'min:0.001']],
        ['name' => 'paid_amount', 'label' => 'المبلغ المدفوع', 'type' => 'money', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'starts_on', 'label' => 'بداية الخصم', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'status', 'label' => 'الحالة', 'type' => 'select', 'options' => ['active' => 'نشط', 'settled' => 'مغلق'], 'rules' => ['required', 'in:active,settled']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];
}
