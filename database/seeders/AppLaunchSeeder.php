<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;
use Spatie\Permission\Models\Role;

class AppLaunchSeeder extends Seeder
{
    /**
     * Run the database seeds for application launch.
     * Contains essential data: Permissions, Settings, Base Admin.
     */
    public function run(): void
    {
        // 1. Permissions & Roles
        $this->call(PermissionsSeeder::class);
        $this->call(RolesSeeder::class);

        // 2. Settings
        $this->call(SettingsSeeder::class);

        // 3. Create Super Administrator
        $superAdminRole = Role::where('name', 'Super Admin')->firstOrFail();

        $admin = User::firstOrCreate(
            ['email' => 'admin@university.edu'],
            [
                'username' => 'admin',
                'password' => Hash::make('secret'),
                'first_name' => 'System',
                'last_name' => 'Admin',
                'display_name' => 'Super Administrator',
                'active' => true,
            ]
        );

        if (!$admin->hasRole($superAdminRole)) {
            $admin->assignRole($superAdminRole);
        }

        $this->command->info('Application launched successfully! Admin: admin@university.edu / secret');
    }
}
