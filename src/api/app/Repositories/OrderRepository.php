<?php

namespace App\Repositories;

use App\Models\Tenant\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Order;
        parent::__construct();
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?string $fromDate = null,
        ?string $toDate = null
    ): Collection {
        return Order::when($search, function ($query, $search) {
            $query->whereHas('supplier', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })
                ->orWhere('invoice_code', 'LIKE', "%{$search}%");
        })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('invoice_date', [$fromDate, $toDate]);
            })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }

    public function findWithOrderItems(int $orderId)
    {
        return Order::where('id', $orderId)->with('order_items');
    }

    public function ordersCount(): int
    {
        return $this->findAll()->count();
    }
}
