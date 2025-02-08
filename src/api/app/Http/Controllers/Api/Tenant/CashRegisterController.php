<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CashRegister\OpenCashRegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\CashRegister;
use App\Services\CashRegisterService;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CashRegisterController extends ApiController
{
    private CashRegisterService $cashRegisterService;

    public function __construct(CashRegisterService $cashRegisterService)
    {
        $this->cashRegisterService = $cashRegisterService;
        $this->model = new CashRegister;
        parent::__construct($cashRegisterService);
    }

    public function getCashRegisters(SearchRequest $request)
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $cashRegisters = $this->cashRegisterService->search(
                $offset,
                $limit,
                $search,
                $sortBy,
                $sortOrder
            );

            return $this->successResponse(
                $cashRegisters,
                class_basename($this->model).'s successfully retrieved!',
                Response::HTTP_OK,
                $this->cashRegisterService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function openCashRegister(OpenCashRegisterRequest $request): JsonResponse
    {
        $content = $request->safe()->toArray();
        $content['openedBy'] = $request->user()->id;

        $cashRegister = $this->cashRegisterService->create($content);

        return $this->successResponse($cashRegister, 'Cash register successfully opened!');
    }

    public function closeCashRegister(OpenCashRegisterRequest $request, int $cashRegisterId)
    {
        $content = $request->safe()->toArray();
        $content['closedBy'] = $request->user()->id;
        $content['closingTime'] = (new DateTime('now'))->format('Y-m-d H:i:s');

        $updatedCashRegister = $this->cashRegisterService->update($cashRegisterId, $content);

        return $this->successResponse($updatedCashRegister, 'Cash register successfully closed!');
    }
}
