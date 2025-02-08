<?php

namespace App\Repositories;

use App\Enums\InventoryType;
use App\Models\Tenant\Inventory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Inventory;
        parent::__construct();
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?int $type = null
    ): Collection {
        return Inventory::when($search, function ($query, $search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('label', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        })
            ->when($type !== null, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }

    public function getBatchQoh(Inventory $inventory): int
    {
        return Inventory::where('product_id', $inventory->product_id)
            ->where('batch_id', $inventory->batch_id)
            ->sum('quantity');
    }

    public function getAvailableBatches(string $productId): Collection
    {
        return Inventory::where('product_id', $productId)
            ->where('type', InventoryType::IN->value)
            ->where('actual_quantity', '>', 0)
            ->where('expiry_date', '>', Carbon::now())
            ->orderBy('expiry_date', 'asc')
            ->get();
    }
}
