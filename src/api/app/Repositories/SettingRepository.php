<?php

namespace App\Repositories;

use App\Models\Tenant\Setting;

class SettingRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Setting();
        parent::__construct();
    }

    public function updateSetting(string $key, string $value): string
    {
        $setting = Setting::where('key', $key)->get();
        $setting->value = $value;
        $setting->save();

        return $setting->id;
    }
}
