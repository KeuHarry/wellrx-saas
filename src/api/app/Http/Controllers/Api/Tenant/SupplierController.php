<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Supplier;
use App\Services\SupplierService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SupplierController extends ApiController
{
    private SupplierService $supplierService;
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
        $this->model = new Supplier();
        parent::__construct();
    }

    public function createSupplier(Request $request) 
    {
        $content = $request->all();

        $validator = Validator::make($content, [
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $supplier = $this->supplierService->createSupplier(
            $content['name'],
            $content['address'],
            $content['city'],
            $content['contact'],
            $content['phone'],
            $content['fax'],
            $content['email'],
            $content['postalCode']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'supplier' => $supplier
        ]);
    }

    public function getSupplier(int $supplierId)
    {
        $supplier = $this->supplierService->getSupplier($supplierId);

        if(! isset($supplier)){
            throw new ObjectNotFountException($supplierId, 'Supplier');
        }
        return $this->successResponse($supplier);
    }

    public function getSuppliers(SearchRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $suppliers = $this->supplierService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($suppliers instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($suppliers),
            'data' => $suppliers
        ]);
    }

    public function deleteSupplier(int $supplierId)
    {
        $supplier = $this->supplierService->getSupplier($supplierId);

        if(! isset($supplier)){
            throw new ObjectNotFountException($supplierId, 'Supplier');
        }

        if($this->supplierService->deleteSupplier($supplier->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'supplier' => $supplierId,
                'message' => 'Supplier successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSupplier(Request $request, int $supplierId)
    {
        $supplier = $this->supplierService->getSupplier($supplierId);

        if(! isset($supplier)){
            throw new ObjectNotFountException($supplierId, 'Supplier');
        }

        $content = $request->all();

        $validator = Validator::make($content, [
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $updatedSupplier = $this->supplierService->updateSupplier(
            $supplier,
            $content['name'],
            $content['address'],
            $content['city'],
            $content['contact'],
            $content['phone'],
            $content['fax'],
            $content['email'],
            $content['postalCode']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'supplier' => $updatedSupplier
        ]);
    }
}
