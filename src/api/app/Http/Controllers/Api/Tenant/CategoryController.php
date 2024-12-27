<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFoundException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Category;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends ApiController
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->model = new Category();
        parent::__construct();
    }

    public function createCategory(CategoryRequest $request) 
    {
        $content = $request->safe();

        $category = $this->categoryService->createCategory(
            $content['name'],
            $content['markup'],
            $content['description']
        );

        return new JsonResponse([
            'succes' => true,
            'message' => 'Category created successfully.',
            'data' => $category,
            'errors' => null
        ]);
    }

    public function getCategory(int $categoryId)
    {
        $category = $this->categoryService->getCategory($categoryId);

        return $this->successResponse($category);
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

        return new JsonResponse([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'total' => empty($search) ? count($this->model->all()) : count($categories),
            'data' => $categories,
            'errors' => null
        ]);
    }

    public function deleteCategory(int $categoryId): JsonResponse
    {
        $category = $this->categoryService->getCategory($categoryId);

        if(! isset($category)){
            throw new ObjectNotFoundException($categoryId, 'Category');
        }

        if(!$this->categoryService->deleteCategory($category->id)){
            return new JsonResponse([
                'success' => false,
                'data' => null,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'success' => true,
            'data' => $categoryId,
            'message' => 'Category successfully deleted.',
            'errors' => null
        ]);
    }

    public function updateCategory(CategoryRequest $request, int $categoryId)
    {
        $content = $request->safe();

        $updatedCategory = $this->categoryService->updateCategory(
            $categoryId,
            $content['name'],
            $content['markup'],
            $content['description']
        );

        return new JsonResponse([
            'success' => true,
            'message' => 'Category successfully updated.',
            'data' => $updatedCategory,
            'errors' => null
        ]);
    }
}
