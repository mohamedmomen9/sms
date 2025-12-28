<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@university.edu');
        $password = env('ADMIN_PASSWORD', 'secret');

        /** @var User $user */
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'username' => 'admin',
                'display_name' => 'System Admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_admin' => true, // Ensure this is set
                'email_verified_at' => now(),
            ]
        );
        
        // Assign Spatie role if it exists
        if (Role::where('name', 'Super Admin')->exists()) {
            $user->assignRole('Super Admin');
        }
        
        $this->command->info("Admin user seeded successfully.");
        $this->command->info("Email: {$email}");
        $this->command->info("Password: {$password}");
    }
}
