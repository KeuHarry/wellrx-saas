<?php

namespace App\Repositories;

use App\Models\Tenant\Intermediary;
use Illuminate\Database\Eloquent\Collection;

class IntermediaryRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Intermediary;
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return Intermediary::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }
}
