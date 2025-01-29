<?php

namespace App\Services;

use App\Enums\InventoryType;
use App\Models\Tenant\Inventory;
use App\Repositories\InventoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class InventoryService extends ApiService
{
    public function __construct(private InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
        parent::__construct($inventoryRepository);
    }

    public function getInventories(): Collection
    {
        return Inventory::all();
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
        return $this->inventoryRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy,
            $type
        );
    }

    public function getBatchQoh(Inventory $inventory): int
    {
        return $this->inventoryRepository->getBatchQoh($inventory);
    }

    public function updateBatchQoh(Inventory $inventory)
    {
        return $this->inventoryRepository->updateBatchQoh($inventory);
    }
}
