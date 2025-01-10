<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Insurer\InsurerRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Insurer;
use App\Services\InsuranceService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InsurerController extends ApiController
{
    private InsuranceService $insuranceService;

    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
        $this->model = new Insurer;
        parent::__construct();
    }

    public function createInsurer(InsurerRequest $request)
    {
        $content = $request->all();

        $insurer = $this->insuranceService->createInsurer($content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'insurer' => $insurer,
        ]);
    }

    public function getInsurer(int $insurerId)
    {
        $insurer = $this->insuranceService->getInsurer($insurerId);

        if (! isset($insurer)) {
            throw new ObjectNotFountException($insurerId, 'Insurer');
        }

        return $this->successResponse($insurer);
    }

    public function getInsurers(SearchRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $insurers = $this->insuranceService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($insurers instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($insurers),
            'data' => $insurers,
        ]);
    }

    public function deleteInsurer(int $insurerId)
    {
        $insurer = $this->insuranceService->getInsurer($insurerId);

        if (! isset($insurer)) {
            throw new ObjectNotFountException($insurerId, 'Insurer');
        }

        if ($this->insuranceService->deleteInsurer($insurer->id)) {
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'insurer' => $insurerId,
                'message' => 'Insurer successfully deleted.',
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateInsurer(InsurerRequest $request, int $insurerId)
    {
        $insurer = $this->insuranceService->getInsurer($insurerId);

        if (! isset($insurer)) {
            throw new ObjectNotFountException($insurerId, 'Insurer');
        }

        $content = $request->all();

        $updatedInsurer = $this->insuranceService->updateInsurer($insurerId, $content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'insurer' => $updatedInsurer,
            'message' => 'Insurer successfully updated.',
        ]);
    }
}
