<?php

namespace App\Repositories;

use App\Models\Tenant\SaleItem;

class SaleItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new SaleItem;
        parent::__construct();
    }
}
