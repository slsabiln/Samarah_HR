<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('HR_DEFAULT_USER_EMAIL', 'nabeel@hr.local')],
            [
                'name' => env('HR_DEFAULT_USER_NAME', 'نبيل السنفي'),
                'password' => Hash::make(env('HR_DEFAULT_USER_PASSWORD', 'password')),
            ]
        );
    }
}
