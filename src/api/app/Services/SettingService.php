<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService extends ApiService
{
    public function __construct(private SettingRepository $settingRepository)
    {
        parent::__construct($settingRepository);
    }
}
