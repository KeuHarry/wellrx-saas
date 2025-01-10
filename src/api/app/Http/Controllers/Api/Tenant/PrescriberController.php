<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PrescriberRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Prescriber;
use App\Services\PrescriberService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PrescriberController extends ApiController
{
    private PrescriberService $prescriberService;

    public function __construct(PrescriberService $prescriberService)
    {
        $this->prescriberService = $prescriberService;
        $this->model = new Prescriber();
        parent::__construct($prescriberService);
    }

    public function getPrescribers(SearchRequest $request)
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $users = $this->prescriberService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse($users, class_basename($this->model).'s successfully retrieved!', Response::HTTP_OK, $this->prescriberService->getModelCount());
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createPrescriber(PrescriberRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updatePrescriber(PrescriberRequest $request, int $prescriberId): JsonResponse
    {
        return $this->update($prescriberId, $request);
    }
}
