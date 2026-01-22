<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core System Setup
        $this->call(AppLaunchSeeder::class);

        // Optional: Modules Seeders can be added here
        // \Modules\Campus\Database\Seeders\CampusSeeder::class

        // Demo Data (Local/Staging only)
        if (!App::isProduction()) {
            // $this->call(DemoDataSeeder::class);
        }
    }
}
