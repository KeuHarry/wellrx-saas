<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
use App\Models\Tenant\Shelf;
use App\Repositories\ShelfRepository;
use Illuminate\Database\Eloquent\Collection;

class ShelfService extends ApiService
{
    public function __construct(private ShelfRepository $shelfRepository)
    {
        parent::__construct($shelfRepository);
    }

    public function search(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        int $offset = 0,
        int $limit = 10
    ): Collection {

        $shelves = Shelf::where('name', 'LIKE', '%'.$search.'%')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        if (! assert($shelves instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $shelves;
    }
}
