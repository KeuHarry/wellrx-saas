<?php

namespace App\Repositories;

use App\Models\Tenant\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{


    public function findAll(): Collection
    {
        return Category::all();
    }

    public function create(string $name, float $markup, string $description): Category
    {
        $category = new Category();
        $category->name = $name;
        $category->markup = $markup;
        $category->description = $description;
        $category->save();

        return $category;
    }

    public function update(Category $category, string $name, float $markup, string $description): int
    {
        $category->name = $name;
        $category->markup = $markup;
        $category->description = $description;
        $category->save();

        return $category->id;
    }

    public function findById(int $categoryId): ?Category
    {
        return Category::find($categoryId);
    }

    public function delete(int $categoryId): int
    {
        return Category::destroy($categoryId);
    }
}
