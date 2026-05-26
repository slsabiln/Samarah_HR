<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:3',
            'monthly_installment' => 'decimal:3',
            'paid_amount' => 'decimal:3',
            'starts_on' => 'date',
        ];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->amount - (float) $this->paid_amount);
    }
}
