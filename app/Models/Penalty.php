<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'points' => 'integer',
            'deduction_amount' => 'decimal:3',
        ];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
