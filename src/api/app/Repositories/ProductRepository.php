<?php

namespace App\Repositories;

use App\Models\Tenant\Inventory;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Product();
        parent::__construct();
    }

    public function search(
        int $offset = 0, 
        int $limit = 10, 
        string $sortOrder = 'ASC', 
        ?string $search = null, 
        ?string $sortBy = null,
        ?int $shelfId = null
    ): Collection
    {
        return Product::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('product_code', 'LIKE', "%{$search}%")
                    ->orWhere('label', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        })
        ->when($shelfId, function ($query, $shelfId) {
            $query->where('shelf_id', $shelfId);
        })
        ->offset($offset)
        ->limit($limit)
        ->orderBy($sortBy, $sortOrder)
        ->get();
    }

    public function calculateQoh(int $productId): int
    {
        return Inventory::where('product_id', $productId)->sum('quantity');
    } 

    public function updateProductQoh(int $productId): void
    {
        $sum = $this->calculateQoh($productId);
        Product::where('id', $productId)->update(['qoh' => $sum]);
    }
}
