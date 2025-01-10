<?php

namespace App\Services;

use App\Models\Tenant\Supplier;
use App\Repositories\SupplierRepository;
use Illuminate\Database\Eloquent\Collection;

class SupplierService extends ApiService
{
    public function __construct(private SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
        parent::__construct($supplierRepository);
    }

    public function getSuppliers(): Collection
    {
        return Supplier::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->supplierRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }
}
