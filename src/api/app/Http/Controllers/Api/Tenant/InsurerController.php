<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\InsurerRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Insurer;
use App\Services\InsuranceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class InsurerController extends ApiController
{
    private InsuranceService $insuranceService;

    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
        $this->model = new Insurer();
        parent::__construct($insuranceService);
    }

    public function getInsurers(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $insurers = $this->insuranceService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse(
                $insurers, 
                class_basename($this->model).'s successfully retrieved!',
                Response::HTTP_OK,
                $this->insuranceService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createInsurer(InsurerRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateInsurer(InsurerRequest $request, int $insurerId): JsonResponse
    {
        return $this->update($insurerId, $request);
    }
}
