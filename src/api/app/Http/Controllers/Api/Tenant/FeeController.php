<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Models\Tenant\Fee;
use App\Services\FeeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class FeeController extends ApiController
{
    private FeeService $feeService;
    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
        $this->model = new Fee();
        parent::__construct();
    }

    public function createFee(Request $request) 
    {
        $content = $request->all();

        $validator = Validator::make($content, [
            'label' => 'required|string',
            'cost' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $fee = $this->feeService->createFee(
            $content['label'],
            $content['cost'],
            $content['description']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'fee' => $fee
        ]);
    }

    public function getFee(int $feeId)
    {
        $fee = $this->feeService->getFee($feeId);

        if(! isset($fee)){
            throw new ObjectNotFountException($feeId, 'Fee');
        }
        return $this->successResponse($fee);
    }

    public function getFees(Request $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $fees = $this->feeService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($fees instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($fees),
            'data' => $fees
        ]);
    }

    public function deleteFee(int $feeId)
    {
        $fee = $this->feeService->getFee($feeId);

        if(! isset($fee)){
            throw new ObjectNotFountException($feeId, 'Fee');
        }

        if($this->feeService->deleteFee($fee->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'fee' => $feeId,
                'message' => 'Fee successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateFee(Request $request, int $feeId)
    {
        $fee = $this->feeService->getFee($feeId);

        if(! isset($fee)){
            throw new ObjectNotFountException($feeId, 'Fee');
        }

        $content = $request->all();

        $validator = Validator::make($content, [
            'label' => 'required|string',
            'cost' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $updatedFee = $this->feeService->updateFee(
            $fee,
            $content['label'],
            $content['cost'],
            $content['description']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'fee' => $updatedFee
        ]);
    }
}
