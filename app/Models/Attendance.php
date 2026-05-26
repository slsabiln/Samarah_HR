<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'worked_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
