<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Customer;
use App\Services\CustomerService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CustomerController extends ApiController
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->model = new Customer;
        parent::__construct($customerService);
    }

    public function getCustomers(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $customers = $this->customerService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse(
                $customers,
                class_basename($this->model).'s successfully retrieved!',
                Response::HTTP_OK,
                $this->customerService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createCustomer(CustomerRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateCustomer(CustomerRequest $request, int $customerId): JsonResponse
    {
        return $this->update($customerId, $request);
    }
}
