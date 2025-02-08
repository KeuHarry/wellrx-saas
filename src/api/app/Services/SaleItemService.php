<?php

namespace App\Services;

use App\Models\Tenant\SaleItem;
use App\Repositories\SaleItemRepository;
use Illuminate\Database\Eloquent\Collection;

class SaleItemService extends ApiService
{
    public function __construct(private SaleItemRepository $saleItemRepository)
    {
        $this->saleItemRepository = $saleItemRepository;
        parent::__construct($saleItemRepository);
    }

    public function getSaleItems(): Collection
    {
        return SaleItem::all();
    }
}
