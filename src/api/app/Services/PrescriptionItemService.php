<?php

namespace App\Services;

use App\Models\Tenant\PrescriptionItem;
use App\Models\Tenant\PrescriptionItemLabelCode;
use App\Repositories\PrescriptionItemLabelCodeRepository;
use App\Repositories\PrescriptionItemRepository;

class PrescriptionItemService extends ApiService
{
    public function __construct(
        private PrescriptionItemRepository $prescriptionItemRepository,
        private PrescriptionItemLabelCodeRepository $prescriptionItemLabelCodeRepository
    ) {
        $this->prescriptionItemRepository = $prescriptionItemRepository;
        parent::__construct($prescriptionItemRepository);
    }

    public function bulkCreate(array $contents): array
    {
        $prescriptionItemIds = [];
        foreach ($contents as $content) {
            $labelCodes = $content['labelCodes'] ?? [];
            unset($content['labelCodes']);
            $input = $this->updateKey($content);
            $prescriptionItem = $this->prescriptionItemRepository->create($input);
            assert($prescriptionItem instanceof PrescriptionItem);
            $prescriptionItemIds[] = $prescriptionItem->id;

            if (! empty($labelCodes)) {
                $prescriptionItemLabelCodes = array_map(function ($labelCode) use ($prescriptionItem) {
                    return [
                        'prescription_item_id' => $prescriptionItem->id,
                        'label_code_id' => $labelCode['id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $labelCodes);
                PrescriptionItemLabelCode::insert($prescriptionItemLabelCodes);
            }
        }

        return $prescriptionItemIds;
    }
}
