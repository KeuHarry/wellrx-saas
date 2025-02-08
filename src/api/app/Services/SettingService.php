<?php

namespace App\Services;

use App\Models\Tenant\Setting;
use App\Repositories\SettingRepository;

class SettingService extends ApiService
{
    public function __construct(private SettingRepository $settingRepository)
    {
        parent::__construct($settingRepository);
    }

    public function getByKey(string $key): ?Setting
    {
        return $this->settingRepository->findByKey($key);
    }

    public function getPharmacyDetails(): array
    {
        $settings = $this->settingRepository->findAll();

        return $settings->pluck('value', 'key')->toArray();
    }
}
