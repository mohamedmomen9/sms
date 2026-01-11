<?php

namespace Modules\Teachers\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Teachers\Models\Teacher;

class TeachersDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Teacher::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Demo Teacher',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
                'qualification' => 'PhD in Computer Science',
            ]
        );

        $this->command->info("Demo teacher created:");
        $this->command->info("  Email: teacher@example.com");
        $this->command->info("  Password: password");
        $this->command->info("  Login URL: /teacher/login");
    }
}
