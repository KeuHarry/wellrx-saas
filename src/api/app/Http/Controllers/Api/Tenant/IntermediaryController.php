<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\IntermediaryRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Intermediary;
use App\Services\IntermediaryService;
use Illuminate\Http\JsonResponse;

class IntermediaryController extends ApiController
{
    private IntermediaryService $intermediaryService;

    public function __construct(IntermediaryService $intermediaryService)
    {
        $this->intermediaryService = $intermediaryService;
        $this->model = new Intermediary();
        parent::__construct($intermediaryService);
    }

    public function getIntermediaries(SearchRequest $request): JsonResponse
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $intermediaries = $this->intermediaryService->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );

        return $this->successResponse($intermediaries, total: $this->intermediaryService->getModelCount());
    }

    public function createIntermediary(IntermediaryRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateIntermediary(IntermediaryRequest $request, int $intermediaryId)
    {
        return $this->update($intermediaryId, $request);
    }
}
