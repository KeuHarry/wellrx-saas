<?php

namespace App\Repositories;

use App\Models\Tenant\PrescriptionItemLabelCode;

class PrescriptionItemLabelCodeRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new PrescriptionItemLabelCode;
        parent::__construct();
    }
}
