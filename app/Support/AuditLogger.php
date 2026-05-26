<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AuditLogger
{
    public static function record(string $action, Model $model, ?array $oldValues = null, ?array $newValues = null): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $model::class,
            'auditable_id' => (string) $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues ?: $model->getAttributes(),
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
