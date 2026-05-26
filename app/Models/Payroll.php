<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:3',
            'allowances' => 'decimal:3',
            'overtime_amount' => 'decimal:3',
            'leave_deduction' => 'decimal:3',
            'penalty_deduction' => 'decimal:3',
            'loan_deduction' => 'decimal:3',
            'gross_salary' => 'decimal:3',
            'net_salary' => 'decimal:3',
            'paid_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
