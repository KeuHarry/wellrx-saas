<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LabelRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\LabelCode;
use App\Services\LabelCodeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LabelCodeController extends ApiController
{
    private LabelCodeService $labelCodeService;

    public function __construct(LabelCodeService $labelCodeService)
    {
        $this->labelCodeService = $labelCodeService;
        $this->model = new LabelCode();
        parent::__construct($labelCodeService);
    }

    public function getLabelCodes(SearchRequest $request)
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $labelCodes = $this->labelCodeService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse(
                $labelCodes, 
                class_basename($this->model).'s successfully retrieved!', 
                Response::HTTP_OK, 
                $this->labelCodeService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createLabelCode(LabelRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateLabelCode(LabelRequest $request, int $labelCodeId): JsonResponse
    {
        return $this->update($labelCodeId, $request);
    }
}
