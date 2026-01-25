<?php

namespace Modules\System\Services;

use Modules\System\Models\SystemSetting;

class SystemSettingService
{
    public function get(string $key, $default = null)
    {
        return SystemSetting::getValue($key, $default);
    }

    public function set(string $key, $value, ?string $type = null, string $group = 'general'): void
    {
        SystemSetting::setValue($key, $value, $type ?? 'string', $group);
    }
}
