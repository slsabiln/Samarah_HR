<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function __invoke(): View
    {
        return view('audit.index', [
            'logs' => AuditLog::with('user')->latest()->paginate(20),
        ]);
    }
}
