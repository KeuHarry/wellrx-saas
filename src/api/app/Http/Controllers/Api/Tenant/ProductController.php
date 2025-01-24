<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProductController extends ApiController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->model = new Product();
        parent::__construct($productService);
    }

    public function getProducts(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $shelfId = intval($request->query->get('shelfId'));

            $products = $this->productService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy,
                $shelfId
            );

            return $this->successResponse(
                $products, 
                class_basename($this->model).'s successfully retrieved!',
                Response::HTTP_OK,
                $this->productService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createProduct(ProductRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateProduct(ProductRequest $request, int $productId): JsonResponse
    {
        return $this->update($productId, $request);
    }
}
