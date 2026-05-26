<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Loan;
use App\Models\OfficialDocument;
use App\Models\Payroll;
use App\Models\Penalty;
use App\Models\Training;
use App\Support\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::unguard();

        Blade::directive('kwd', fn ($expression) => "<?php echo money_kwd($expression); ?>");

        foreach ([
            Employee::class,
            LeaveRequest::class,
            Loan::class,
            OfficialDocument::class,
            Penalty::class,
            Attendance::class,
            Training::class,
            Payroll::class,
        ] as $model) {
            $model::created(fn (Model $record) => AuditLogger::record('created', $record));
            $model::updated(fn (Model $record) => AuditLogger::record('updated', $record, $record->getOriginal(), $record->getChanges()));
            $model::deleted(fn (Model $record) => AuditLogger::record('deleted', $record, $record->getOriginal()));
        }
    }
}
