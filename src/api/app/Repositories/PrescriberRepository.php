<?php

namespace App\Repositories;

use App\Models\Tenant\Prescriber;
use Illuminate\Database\Eloquent\Collection;

class PrescriberRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Prescriber;
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return Prescriber::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('registration_number', 'LIKE', "%{$search}%");
            });
        })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }
}
