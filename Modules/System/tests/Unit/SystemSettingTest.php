<?php

namespace Modules\System\Tests\Unit;

use Modules\System\Models\SystemSetting;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_set_and_get_setting()
    {
        SystemSetting::setValue('site_name', 'My School');

        $this->assertEquals('My School', SystemSetting::getValue('site_name'));
    }

    public function test_can_set_typed_setting()
    {
        SystemSetting::setValue('maintenance_mode', true, 'boolean');

        $this->assertTrue(SystemSetting::getValue('maintenance_mode'));

        SystemSetting::setValue('max_items', 10, 'integer');
        $this->assertEquals(10, SystemSetting::getValue('max_items'));
    }
}
