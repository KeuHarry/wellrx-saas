<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Models\Tenant\Tax;
use App\Services\TaxService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TaxController extends ApiController
{
    private TaxService $taxService;
    public function __construct(TaxService $taxService)
    {
        $this->taxService = $taxService;
        $this->model = new Tax();
        parent::__construct();
    }

    public function createTax(Request $request) 
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

        $tax = $this->taxService->createTax(
            $content['label'],
            $content['cost'],
            $content['description']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'tax' => $tax
        ]);
    }

    public function getTax(int $taxId)
    {
        $tax = $this->taxService->getTax($taxId);

        if(! isset($tax)){
            throw new ObjectNotFountException($taxId, 'Tax');
        }
        return $this->successResponse($tax);
    }

    public function getTaxes(Request $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $taxes = $this->taxService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($taxes instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($taxes),
            'data' => $taxes
        ]);
    }

    public function deleteTax(int $taxId)
    {
        $tax = $this->taxService->getTax($taxId);

        if(! isset($tax)){
            throw new ObjectNotFountException($taxId, 'Tax');
        }

        if($this->taxService->deleteTax($tax->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'tax' => $taxId,
                'message' => 'Tax successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateTax(Request $request, int $taxId)
    {
        $tax = $this->taxService->getTax($taxId);

        if(! isset($tax)){
            throw new ObjectNotFountException($taxId, 'Tax');
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

        $updatedTax = $this->taxService->updateTax(
            $tax,
            $content['label'],
            $content['cost'],
            $content['description']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'tax' => $updatedTax
        ]);
    }
}
