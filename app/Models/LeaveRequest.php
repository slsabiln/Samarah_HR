<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'paid' => 'boolean',
            'days' => 'integer',
        ];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
