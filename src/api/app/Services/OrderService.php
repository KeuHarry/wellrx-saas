<?php

namespace App\Services;

use App\Enums\InventoryType;
use App\Enums\OrderStatus;
use App\Models\Tenant\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService extends ApiService
{
    public function __construct(private OrderRepository $orderRepository, private InventoryService $inventoryService)
    {
        $this->orderRepository = $orderRepository;
        $this->inventoryService = $inventoryService;
        parent::__construct($orderRepository);
    }

    public function getOrders(): Collection
    {
        return Order::all();
    }

    public function search(
        int $offset = 0, 
        int $limit = 10, 
        string $sortOrder = 'ASC', 
        ?string $search = null, 
        ?string $sortBy = null,
        ?string $fromDate = null,
        ?string $toDate = null
    ): Collection
    {
        return $this->orderRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy,
            $fromDate,
            $toDate
        );
    }

    public function completeOrder(int $orderId): Order
    {
        $order = $this->orderRepository->findById($orderId);
        $orderItems = $order->order_items;

        $inventoriesData = [];
        foreach ($orderItems as $orderItem) {
            $inventoriesData[] = [
                'productId' => $orderItem->product_id,
                'quantity' => $orderItem->quantity,
                'type' => InventoryType::IN->value,
                'expiryDate' => $orderItem->expiry_date,
                'acqCost' => $orderItem->wholesale_cost,
                'wholesaleCost' => $orderItem->wholesale_cost,
                'retailCost' => $orderItem->retail_cost,
                'unitCost' => $orderItem->retail_cost,
                'subTotal' => $orderItem->quantity * $orderItem->wholesale_cost,
                'supplierId' => $order->supplier->id,
                'orderItemId' => $orderItem->id
            ];
        }

        DB::transaction(function () use ($order, $inventoriesData) {
            $order->update(['status' => OrderStatus::COMPLETED->value]);
            foreach ($inventoriesData as $inventoryData) {
                $this->inventoryService->create($inventoryData);
            }
        });

        return $order;
    }
}
