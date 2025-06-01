<?php

namespace App\Repositories;

use App\Enums\InventoryType;
use App\Http\Resources\InStockProductResource;
use App\Models\Tenant\Inventory;
use App\Models\Tenant\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Product;
        parent::__construct();
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?int $shelfId = null
    ): array {
        $query = Product::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('product_code', 'LIKE', "%{$search}%")
                    ->orWhere('label', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        })
            ->when($shelfId, function ($query, $shelfId) {
                $query->where('shelf_id', $shelfId);
            });

        $total = $query->count();

        $products = $query->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        return [
            'products' => $products,
            'total' => $total,
        ];
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

    public function findInStockProducts(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null
    ): AnonymousResourceCollection {
        $inStockProducts = Product::where('qoh', '>', 0)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                $query->where('label', 'LIKE', "%{$search}%");
            })
            ->with(['shelf', 'inventories' => function ($query) {
                $query->where('type', InventoryType::IN->value);
            }])
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        return InStockProductResource::collection($inStockProducts);
    }

    public function productsCount(): int
    {
        return $this->findAll()->count();
    }
}
