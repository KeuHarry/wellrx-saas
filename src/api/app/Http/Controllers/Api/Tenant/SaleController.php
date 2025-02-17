<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\OneTimePaymentRequest;
use App\Http\Requests\PartialPaymentRequest;
use App\Http\Requests\SaleRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Sale;
use App\Services\SaleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SaleController extends ApiController
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
        $this->model = new Sale;
        parent::__construct($saleService);
    }

    public function getSales(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $type = $request->query->get('type');
            $status = $request->query->get('status');
            $fromDate = $request->query->get('startDate');
            $toDate = $request->query->get('endDate');
            $cashierStatus = $request->query->get('cashierStatus');

            $result = $this->saleService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy,
                $type,
                $status,
                $fromDate,
                $toDate,
                $cashierStatus
            );

            $total = $result['total'];
            $sales = $result['sales'];
            assert($sales instanceof AnonymousResourceCollection);

            return $this->successResponse(
                $sales,
                'Sales successfully retrieved!',
                Response::HTTP_OK,
                $total
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function getUnPaidSales(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $type = $request->query->get('type');
            $fromDate = $request->query->get('startDate');
            $toDate = $request->query->get('endDate');
            $cashierStatus = $request->query->get('cashierStatus');

            $sales = $this->saleService->getUnPaidSales(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy,
                $type,
                $fromDate,
                $toDate,
                $cashierStatus
            );

            return $this->successResponse(
                $sales,
                'Sales successfully retrieved!',
                Response::HTTP_OK,
                $this->saleService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createSale(SaleRequest $request): JsonResponse
    {
        try {
            $content = $request->safe()->toArray();
            $sale = $this->saleService->createSale($content);

            return $this->successResponse($sale, class_basename($this->model).' successfully created');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create '.class_basename($this->model));
        }
    }

    public function paySale(OneTimePaymentRequest $request): JsonResponse
    {
        try {
            $content = $request->safe()->toArray();
            $sale = $this->saleService->paySale($content);

            return $this->successResponse($sale, class_basename($this->model).' successfully paid');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to pay '.class_basename($this->model));
        }
    }

    public function performPartialPayment(PartialPaymentRequest $request): JsonResponse
    {
        try {
            $content = $request->safe()->toArray();
            $sale = $this->saleService->performPartialPayment($content);

            return $this->successResponse($sale, class_basename($this->model).' successfully paid');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create '.class_basename($this->model));
        }
    }
}
