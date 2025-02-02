<?php

namespace App\Services;

use App\Models\Tenant\OrderItem;
use App\Repositories\OrderItemRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderItemService extends ApiService
{
    public function __construct(private OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
        parent::__construct($orderItemRepository);
    }

    public function getOrderItems(): Collection
    {
        return OrderItem::all();
    }
}
