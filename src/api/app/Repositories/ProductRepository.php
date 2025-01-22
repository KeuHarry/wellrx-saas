<?php

namespace App\Repositories;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Product();
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return Product::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('product_code', 'LIKE', "%{$search}%")
                    ->orWhere('label', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        })
        ->offset($offset)
        ->limit($limit)
        ->orderBy($sortBy, $sortOrder)
        ->get();
    }
}
