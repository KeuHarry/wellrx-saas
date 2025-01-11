<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends ApiController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->model = new Category();
        parent::__construct($categoryService);
    }

    public function getCategories(SearchRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $categories = $this->categoryService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        return $this->successResponse($categories, total: $this->categoryService->getModelCount());
    }

    public function createCategory(CategoryRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateCategory(UpdateCategoryRequest $request, int $categoryId)
    {
        return $this->update($categoryId, $request);
    }
}
