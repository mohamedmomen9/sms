<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\System\Models\AppVersion;

class AppVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppVersion::firstOrCreate(
            ['platform' => 'ios'],
            [
                'platform' => 'ios',
                'version' => '1.0.0',
                'min_version' => '1.0.0',
                'force_update' => false,
                'release_notes' => 'Initial release for iOS',
            ]
        );

        AppVersion::firstOrCreate(
            ['platform' => 'android'],
            [
                'platform' => 'android',
                'version' => '1.0.0',
                'min_version' => '1.0.0',
                'force_update' => false,
                'release_notes' => 'Initial release for Android',
            ]
        );
    }
}
