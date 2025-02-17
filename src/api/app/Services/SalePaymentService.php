<?php

namespace App\Services;

use App\Repositories\SalePaymentRepository;

class SalePaymentService extends ApiService
{
    public function __construct(private SalePaymentRepository $salePaymentRepository)
    {
        $this->salePaymentRepository = $salePaymentRepository;
        parent::__construct($salePaymentRepository);
    }
}
