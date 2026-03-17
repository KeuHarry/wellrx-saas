<?php

namespace App\Services;

use App\Models\Tenant\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class ProductService extends ApiService
{
    public function __construct(private ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct($productRepository);
    }

    public function create(array $content): Product
    {
        $labelCodeIds = Arr::get($content, 'labelCodeIds');
        Arr::forget($content, 'labelCodeIds');
        /** @var Product $product */
        $product = parent::create($content);
        if (is_array($labelCodeIds) && ! empty($labelCodeIds)) {
            $product->labelCodes()->sync($labelCodeIds);
        }

        return $product;
    }

    public function update(string $id, array $content): int
    {
        $labelCodeIds = Arr::get($content, 'labelCodeIds');
        Arr::forget($content, 'labelCodeIds');
        $id = parent::update($id, $content);
        if (is_array($labelCodeIds)) {
            Product::find($id)->labelCodes()->sync($labelCodeIds);
        }

        return (int) $id;
    }

    public function getById(int $id): Product
    {
        return Product::with('labelCodes')->findOrFail($id);
    }

    public function getProducts(): Collection
    {
        return Product::all();
    }

    public function getProductsForExport(): Collection
    {
        return Product::with(['category', 'shelf'])->get();
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?int $shelfId = null
    ): array {
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

    public function getProductsInStock(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null
    ): AnonymousResourceCollection {
        return $this->productRepository->findInStockProducts(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }

    public function getGenericProducts(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null
    ): Collection {
        return $this->productRepository->findGenericProducts(
            $offset,
            $limit,
            $search,
            $sortBy,
            $sortOrder
        );
    }
}
