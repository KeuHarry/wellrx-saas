<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends ApiController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->model = new Product;
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

            $result = $this->productService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy,
                $shelfId
            );

            $total = $result['total'];
            $products = $result['products'];
            assert($products instanceof Collection);

            return $this->successResponse(
                $products,
                class_basename($this->model).'s successfully retrieved!',
                Response::HTTP_OK,
                $total
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

    public function getProductsInStock(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $products = $this->productService->getProductsInStock(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
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

    public function getGenericProducts(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $products = $this->productService->getGenericProducts(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse(
                $products,
                class_basename($this->model).'s successfully retrieved!',
                Response::HTTP_OK,
                $products->count()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get products');
        }
    }

    public function exportProducts(): StreamedResponse
    {
        $products = $this->productService->getProductsForExport();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ];

        return ResponseFacade::stream(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Product Code', 'Label', 'Barcode', 'CIP', 'Category', 'Shelf', 'QOH', 'Min', 'Max',
                'Acq Cost', 'Wholesale', 'Retail', 'Unit Cost', 'Active', 'Prescription', 'Generic',
            ]);
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->product_code,
                    $product->label,
                    $product->barcode ?? '',
                    $product->cip_code ?? '',
                    $product->category?->name ?? '',
                    $product->shelf?->name ?? '',
                    $product->qoh,
                    $product->min_quantity ?? '',
                    $product->max_quantity ?? '',
                    $product->acq_cost ?? '',
                    $product->wholesale_cost ?? '',
                    $product->retail_cost ?? '',
                    $product->unit_cost ?? '',
                    $product->is_active ? 'Yes' : 'No',
                    $product->is_prescription ? 'Yes' : 'No',
                    $product->is_generic ? 'Yes' : 'No',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
