<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\FeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Models\Tenant\Fee;
use App\Services\FeeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeeController extends ApiController
{
    private FeeService $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
        $this->model = new Fee;
        parent::__construct($feeService);
    }

    public function createFee(FeeRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateFee(UpdateFeeRequest $request, int $feeId): JsonResponse
    {
        return $this->update($feeId, $request);
    }

    public function getFees(Request $request)
    {
        $search = $request->query->get('search');
        $tag = $request->query->get('tag');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $fees = $this->feeService->search(
            $offset,
            $limit,
            $search,
            $sortBy,
            $sortOrder,
            $tag
        );

        if (! assert($fees instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $this->successResponse($fees, 'Fees successfully retrieved!', Response::HTTP_OK, $this->feeService->getModelCount());
    }
}
