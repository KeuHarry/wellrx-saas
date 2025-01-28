<?php

namespace App\Repositories;

use App\Models\Tenant\Inventory;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Inventory();
        parent::__construct();
    }

    public function search(
        int $offset = 0, 
        int $limit = 10, 
        string $sortOrder = 'ASC', 
        ?string $search = null, 
        ?string $sortBy = null,
        ?int $type = null
    ): Collection
    {
        return Inventory::when($search, function ($query, $search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('label', 'LIKE', "%{$search}%")
                ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        })
        ->when($type, function ($query, $type) {
            $query->where('type', $type);
        })
        ->offset($offset)
        ->limit($limit)
        ->orderBy($sortBy, $sortOrder)
        ->get();
    }
}
