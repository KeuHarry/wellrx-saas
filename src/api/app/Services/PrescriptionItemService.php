<?php

namespace App\Services;

use App\Repositories\PrescriptionItemRepository;

class PrescriptionItemService extends ApiService
{
    public function __construct(private PrescriptionItemRepository $prescriptionItemRepository)
    {
        $this->prescriptionItemRepository = $prescriptionItemRepository;
        parent::__construct($prescriptionItemRepository);
    }
}
