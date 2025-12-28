<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@university.edu');
        $password = env('ADMIN_PASSWORD', 'secret');

        User::updateOrCreate(
            ['email' => $email],
            [
                'username' => 'admin',
                'display_name' => 'System Admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info("Admin user seeded successfully.");
        $this->command->info("Email: {$email}");
    }
}
