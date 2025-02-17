<?php

namespace App\Repositories;

use App\Models\Tenant\SalePayment;

class SalePaymentRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new SalePayment;
        parent::__construct();
    }

    public function findWithMoreDetails(int $id): SalePayment
    {
        $salePayment = SalePayment::with('sale')->findOrFail($id);

        return $salePayment;
    }
}
