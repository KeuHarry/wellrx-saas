<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
use App\Models\Tenant\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService extends ApiService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($categoryRepository);
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ): Collection {
        $categories = Category::where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('category_code', 'LIKE', '%'.$search.'%')
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
