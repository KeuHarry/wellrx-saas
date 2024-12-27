<?php

namespace App\Repositories;

use App\Models\Tenant\Shelf;
use Illuminate\Database\Eloquent\Collection;

class ShelfRepository
{
    public function findAll(): Collection
    {
        return Shelf::all();
    }

    public function create(string $name, string $description): Shelf
    {
        $category = new Shelf();
        $category->name = $name;
        $category->description = $description;
        $category->save();

        return $category;
    }

    public function update(Shelf $shelf, string $name, string $description): int
    {
        $shelf->name = $name;
        $shelf->description = $description;
        $shelf->save();

        return $shelf->id;
    }

    public function findById(int $shelfId): ?Shelf
    {
        return Shelf::find($shelfId);
    }

    public function delete(int $shelfId): int
    {
        return Shelf::destroy($shelfId);
    }
}
