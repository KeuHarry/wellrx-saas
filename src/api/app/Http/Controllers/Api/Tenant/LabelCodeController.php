<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\LabelCode\LabelCodeRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\LabelCode;
use App\Services\LabelCodeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LabelCodeController extends ApiController
{
    private LabelCodeService $labelCodeService;
    public function __construct(LabelCodeService $labelCodeService)
    {
        $this->labelCodeService = $labelCodeService;
        $this->model = new LabelCode();
        parent::__construct();
    }

    public function createLabelCode(LabelCodeRequest $request) 
    {
        $content = $request->all();

        $labelCode = $this->labelCodeService->createLabelCode($content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'labelCode' => $labelCode
        ]);
    }

    public function getLabelCode(int $labelCodeId)
    {
        $labelCode = $this->labelCodeService->getLabelCode($labelCodeId);

        if(! isset($labelCode)){
            throw new ObjectNotFountException($labelCodeId, 'LabelCode');
        }
        return $this->successResponse($labelCode);
    }

    public function getLabelCodes(SearchRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $labelCodes = $this->labelCodeService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($labelCodes instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($labelCodes),
            'data' => $labelCodes
        ]);
    }

    public function deleteLabelCode(int $labelCodeId)
    {
        $labelCode = $this->labelCodeService->getLabelCode($labelCodeId);

        if(! isset($labelCode)){
            throw new ObjectNotFountException($labelCodeId, 'LabelCode');
        }

        if($this->labelCodeService->deleteLabelCode($labelCode->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'labelCode' => $labelCodeId,
                'message' => 'LabelCode successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLabelCode(LabelCodeRequest $request, int $labelCodeId)
    {
        $labelCode = $this->labelCodeService->getLabelCode($labelCodeId);

        if(! isset($labelCode)){
            throw new ObjectNotFountException($labelCodeId, 'LabelCode');
        }

        $content = $request->all();

        $updatedLabelCode = $this->labelCodeService->updateLabelCode($labelCodeId, $content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'labelCode' => $updatedLabelCode,
            'message' => 'Insurer successfully updated.'
        ]);
    }
}
