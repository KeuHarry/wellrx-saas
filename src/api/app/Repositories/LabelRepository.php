<?php

namespace App\Repositories;

use App\Models\Tenant\LabelCode;
use Illuminate\Database\Eloquent\Collection;

class LabelRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new LabelCode;
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return LabelCode::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('label_code', 'LIKE', "%{$search}%")
                    ->orWhere('label', 'LIKE', "%{$search}%");
            });
        })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }
}
