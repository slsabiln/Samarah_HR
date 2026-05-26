<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficialDocument extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'expires_on' => 'date',
        ];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
