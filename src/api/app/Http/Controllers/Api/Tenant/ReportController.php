<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SearchRequest;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ReportController extends ApiController
{
    public function __construct(private ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getPaidSalesReport(SearchRequest $request): JsonResponse
    {
        try {
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $fromDate = $request->query->get('startDate');
            $toDate = $request->query->get('endDate');

            $result = $this->reportService->getPaidSalesReport(
                $offset,
                $limit,
                $sortOrder,
                $sortBy,
                $fromDate,
                $toDate
            );

            $total = intval($result['total']);
            $paidSalesReport = $result['paidSalesReport'];

            return $this->successResponse(
                $paidSalesReport,
                'sales report successfully generated!',
                Response::HTTP_OK,
                $total
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function getProfitLossReport(SearchRequest $request): JsonResponse
    {
        try {
            $fromDate = $request->query->get('startDate');
            $toDate = $request->query->get('endDate');

            $profitLossData = $this->reportService->getProfitLossData(
                $fromDate,
                $toDate
            );

            return $this->successResponse(
                $profitLossData,
                'profit and loss report successfully generated!',
                Response::HTTP_OK
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function getResourcesCount(): JsonResponse
    {
        try {
            $resourcesCount = $this->reportService->getDataCount();

            return $this->successResponse(
                $resourcesCount,
                'resources count retrieved!',
                Response::HTTP_OK
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }
}
