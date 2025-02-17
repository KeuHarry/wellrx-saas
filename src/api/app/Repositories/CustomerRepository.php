<?php

namespace App\Repositories;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Sale;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Customer;
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return Customer::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }

    public function updatePurchasedTotal(int $customerId)
    {
        $customer = $this->findById($customerId);
        assert($customer instanceof Customer);
        $purchasedTotal = Sale::where('customer_id', $customerId)->sum('net_due');
        $customer->updateOrFail(['purchased_total' => $purchasedTotal]);
    }

    public function addBalance(int $customerId, float $amount)
    {
        $customer = $this->findById($customerId);
        assert($customer instanceof Customer);
        $customer->addBalance($amount);
    }
}
