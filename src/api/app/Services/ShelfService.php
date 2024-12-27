<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFoundException;
use App\Models\Tenant\Shelf;
use App\Repositories\ShelfRepository;
use Illuminate\Database\Eloquent\Collection;

class ShelfService
{
    public function __construct(private ShelfRepository $shelfRepository)
    {
        $this->shelfRepository = $shelfRepository;
    }

    public function createShelf(
        string $name,
        string $description
    ): Shelf
    {
        return $this->shelfRepository->create($name, $description);
    }

    public function getShelf(int $shelfId): ?Shelf
    {
        $shelf = $this->shelfRepository->findById($shelfId);

        if(! isset($shelf)){
            throw new ObjectNotFoundException($shelfId, 'Shelf');
        }

        return $shelf;
    }

    public function getShelves(): Collection
    {
        return $this->shelfRepository->findAll();
    }

    public function deleteShelf(int $shelfId): int
    {
        return $this->shelfRepository->delete($shelfId);
    }

    public function updateShelf(string $shelfId, string $name, string $description): int
    {
        $shelf = $this->getShelf($shelfId);
        
        if(! isset($shelf)){
            throw new ObjectNotFoundException($shelfId, 'Shelf');
        }

        return $this->shelfRepository->update($shelf, $name, $description);
    }

    public function search(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        int $offset = 0,
        int $limit = 10
    ): Collection
    {

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
