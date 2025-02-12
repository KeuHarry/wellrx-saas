<?php

namespace App\Repositories;

use App\Models\Tenant\PrescriptionItem;

class PrescriptionItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new PrescriptionItem;
        parent::__construct();
    }
}
