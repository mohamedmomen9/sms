<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default site settings
        Setting::set('site_name', config('app.name', 'Codeness SMS'), 'string', 'site');
        Setting::set('university_name', 'University', 'string', 'site');
        Setting::set('site_logo', '', 'string', 'site');

        $this->command->info('Default settings seeded successfully!');
    }
}
