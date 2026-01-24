<?php

namespace App\Repositories;

use App\Enums\SaleStatus;
use App\Models\Tenant\SaleItem;
use Illuminate\Support\Facades\DB;

class SaleItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new SaleItem;
        parent::__construct();
    }

    public function paidSaleItemsCount(): int
    {
        return DB::table('sale_items as si')
            ->join('sales as s', 'si.sale_id', '=', 's.id')
            ->where('s.status', SaleStatus::PAID->value)->count();
    }
}
