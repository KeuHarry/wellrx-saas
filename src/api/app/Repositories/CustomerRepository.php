<?php

namespace App\Repositories;

use App\Models\Tenant\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Customer();
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
}
