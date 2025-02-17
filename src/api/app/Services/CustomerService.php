<?php

namespace App\Services;

use App\Models\Tenant\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\Collection;

class CustomerService extends ApiService
{
    public function __construct(private CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
        parent::__construct($customerRepository);
    }

    public function getCustomers(): Collection
    {
        return Customer::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->customerRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }

    public function updatePurchasedTotal(int $customerId): void
    {
        $this->customerRepository->updatePurchasedTotal($customerId);
    }

    public function addBalance(int $customerId, float $amount)
    {
        $this->customerRepository->addBalance($customerId, $amount);
    }
}
