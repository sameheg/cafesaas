<?php

namespace App\Support;

use App\Models\SystemSetting;

class SystemSettings
{
    public function get(string $key, $default = null, ?string $scope = null)
    {
        return optional(SystemSetting::where('key', $key)->where('scope', $scope)->first())->value ?? $default;
    }

    public function set(string $key, $value, ?string $scope = null): SystemSetting
    {
        return SystemSetting::updateOrCreate(
            ['key' => $key, 'scope' => $scope],
            ['value' => $value]
        );
    }
}
