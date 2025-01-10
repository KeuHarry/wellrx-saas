<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Tenant\Supplier;
use App\Services\SupplierService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SupplierController extends ApiController
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
        $this->model = new Supplier();
        parent::__construct($supplierService);
    }

    public function getSuppliers(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $users = $this->supplierService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse($users, class_basename($this->model).'s successfully retrieved!');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createSupplier(SupplierRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateSupplier(SupplierRequest $request, int $supplierId): JsonResponse
    {
        return $this->update($supplierId, $request);
    }
}
