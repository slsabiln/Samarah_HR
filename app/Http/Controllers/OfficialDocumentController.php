<?php

namespace App\Http\Controllers;

use App\Models\OfficialDocument;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OfficialDocumentController extends SimpleResourceController
{
    protected string $model = OfficialDocument::class;
    protected string $routeName = 'documents';
    protected string $title = 'الوثائق الرسمية';
    protected string $subtitle = 'متابعة الإقامات، الجوازات، الشهادات الصحية، ورخص القيادة مع تنبيه قبل الانتهاء.';
    protected array $with = ['employee'];
    protected string $orderBy = 'expires_on';
    protected string $orderDirection = 'asc';

    protected array $columns = [
        ['key' => 'employee.name', 'label' => 'الموظف'],
        ['key' => 'type', 'label' => 'نوع الوثيقة'],
        ['key' => 'number', 'label' => 'الرقم'],
        ['key' => 'expires_on', 'label' => 'تاريخ الانتهاء', 'format' => 'date'],
        ['key' => 'status', 'label' => 'الحالة', 'format' => 'status'],
    ];

    protected array $fields = [
        ['name' => 'employee_id', 'label' => 'الموظف', 'type' => 'employee_select', 'rules' => ['required', 'exists:employees,id']],
        ['name' => 'type', 'label' => 'نوع الوثيقة', 'type' => 'select', 'options' => ['residency' => 'إقامة', 'passport' => 'جواز', 'health_certificate' => 'شهادة صحية', 'driver_license' => 'رخصة قيادة', 'other' => 'أخرى'], 'rules' => ['required', 'string']],
        ['name' => 'number', 'label' => 'رقم الوثيقة', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        ['name' => 'issued_on', 'label' => 'تاريخ الإصدار', 'type' => 'date', 'rules' => ['nullable', 'date']],
        ['name' => 'expires_on', 'label' => 'تاريخ الانتهاء', 'type' => 'date', 'rules' => ['required', 'date']],
        ['name' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];

    protected function mutateData(array $data, ?Model $record): array
    {
        $expiresOn = Carbon::parse($data['expires_on']);
        $data['status'] = $expiresOn->isPast() ? 'expired' : ($expiresOn->lte(now()->addDays(45)) ? 'expiring_soon' : 'valid');
        return $data;
    }
}
