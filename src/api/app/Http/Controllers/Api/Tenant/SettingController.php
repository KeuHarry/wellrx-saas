<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SettingRequest;
use App\Models\Tenant\Setting;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends ApiController
{
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
        $this->model = new Setting();
        parent::__construct($settingService);
    }

    public function getSettings(): JsonResponse
    {
        $settings = $this->settingService->getAll();

        return $this->successResponse($settings);
    }

    public function updateSetting(SettingRequest $request): JsonResponse
    {
        $inputs = $request->safe();
        $inputs = $inputs->toArray();

        $settingInputs = $inputs['settings'];
        $caseStatements = '';
        $ids = [];

        foreach ($settingInputs as $input) {
            $id = addslashes($input['id']);
            $value = addslashes($input['value']);
            $ids[] = "'$id'";
            $caseStatements .= "WHEN id = '$id' THEN '$value' ";
        }

        $ids = implode(',', $ids);

        \DB::update("
            UPDATE settings
            SET value = CASE $caseStatements END
            WHERE id IN ($ids)"
        );

        return $this->getSettings();
    }
}
