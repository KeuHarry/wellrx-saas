<?php

namespace App\Services;

use App\Models\Tenant\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends ApiService
{
    public function __construct(private ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct($productRepository);
    }

    public function getProducts(): Collection
    {
        return Product::all();
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
        return $this->productRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy,
            $shelfId
        );
    }

    public function updateQoh(int $productId): void
    {
        $this->productRepository->updateProductQoh($productId);
    }
}
