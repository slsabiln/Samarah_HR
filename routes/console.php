<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('hrm:about', function (): void {
    $this->info('HRM Single User - Laravel 13 / MySQL / KWD');
})->purpose('Show HRM project information');
