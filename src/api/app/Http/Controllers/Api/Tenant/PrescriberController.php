<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Prescriber\PrescriberRequest;
use App\Http\Requests\Prescriber\SearchPrescriberRequest;
use App\Models\Tenant\Prescriber;
use App\Services\PrescriberService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PrescriberController extends ApiController
{
    private PrescriberService $prescriberService;
    public function __construct(PrescriberService $prescriberService)
    {
        $this->prescriberService = $prescriberService;
        $this->model = new Prescriber();
        parent::__construct();
    }

    public function createPrescriber(PrescriberRequest $request) 
    {
        $content = $request->all();

        $prescriber = $this->prescriberService->createPrescriber($content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'prescriber' => $prescriber
        ]);
    }

    public function getPrescriber(int $prescriberId)
    {
        $prescriber = $this->prescriberService->getPrescriber($prescriberId);

        if(! isset($prescriber)){
            throw new ObjectNotFountException($prescriberId, 'Prescriber');
        }
        return $this->successResponse($prescriber);
    }

    public function getPrescribers(SearchPrescriberRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $prescribers = $this->prescriberService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($prescribers instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($prescribers),
            'data' => $prescribers
        ]);
    }

    public function deletePrescriber(int $prescriberId)
    {
        $prescriber = $this->prescriberService->getPrescriber($prescriberId);

        if(! isset($prescriber)){
            throw new ObjectNotFountException($prescriberId, 'Prescriber');
        }

        if($this->prescriberService->deletePrescriber($prescriber->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'prescriber' => $prescriberId,
                'message' => 'Prescriber successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePrescriber(PrescriberRequest $request, int $prescriberId)
    {
        $prescriber = $this->prescriberService->getPrescriber($prescriberId);

        if(! isset($prescriber)){
            throw new ObjectNotFountException($prescriberId, 'Prescriber');
        }

        $content = $request->all();

        $updatedPrescriber = $this->prescriberService->updatePrescriber($prescriberId, $content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'prescriber' => $updatedPrescriber,
            'message' => 'Prescriber successfully updated.'
        ]);
    }
}
