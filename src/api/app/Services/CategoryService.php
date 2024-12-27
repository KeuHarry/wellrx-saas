<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFoundException;
use App\Models\Tenant\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(
        string $name,
        float $markup,
        string $description
    ): Category
    {
        return $this->categoryRepository->create($name, $markup, $description);
    }

    public function getCategory(int $categoryId): ?Category
    {
        $category = $this->categoryRepository->findById($categoryId);

        if(! isset($category)){
            throw new ObjectNotFoundException($categoryId, 'Category');
        }

        return $category;
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->findAll();
    }

    public function deleteCategory(int $categoryId): int
    {
        return Category::destroy($categoryId);
    }

    public function updateCategory(string $categoryId, string $name, float $markup, string $description): int
    {
        $category = $this->getCategory($categoryId);
        
        if(! isset($category)){
            throw new ObjectNotFoundException($categoryId, 'Category');
        }

        return $this->categoryRepository->update($category, $name, $markup, $description);
    }

    public function search(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        int $offset = 0,
        int $limit = 10
    ): Collection
    {
        $categories = Category::where('name', 'LIKE', '%'.$search.'%')
                ->offset($offset)
                ->limit($limit)
                ->orderBy($sortBy, $sortOrder)
                ->get();

        if (! assert($categories instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $categories;
    }
}
