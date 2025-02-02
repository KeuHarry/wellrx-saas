<?php

namespace App\Observers;

use App\Enums\InventoryType;
use App\Models\Tenant\Inventory;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;

class InventoryObserver
{
    protected $productService;
    protected $productRepository;

    public function __construct(ProductService $productService, ProductRepository $productRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

    public function creating(Inventory $inventory)
    {
        $authUser = Auth::user();
        $inventory->created_by = $authUser ? $authUser->id : 1;
        if ($inventory->type === InventoryType::IN->value) {
            $inventory->batch_id = $inventory->getNextBatchId($inventory->product_id);
        }
        $inventory->before_qoh = $inventory->product->qoh;
        $inventory->after_qoh = $inventory->product->qoh + $inventory->quantity;
    }
    
    /**
     * Handle the Inventory "created" event.
     */
    public function created(Inventory $inventory): void
    {
        //
    }

    public function saved(Inventory $inventory)
    {
        $this->productService->updateQoh($inventory->product_id);
    }

    /**
     * Handle the Inventory "updated" event.
     */
    public function updated(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "deleted" event.
     */
    public function deleted(Inventory $inventory): void
    {
        $this->productService->updateQoh($inventory->product_id);
    }

    /**
     * Handle the Inventory "restored" event.
     */
    public function restored(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "force deleted" event.
     */
    public function forceDeleted(Inventory $inventory): void
    {
        //
    }
}
