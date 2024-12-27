<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CashRegister\CloseCashRegisterRequest;
use App\Http\Requests\CashRegister\OpenCashRegisterRequest;
use App\Models\Tenant\CashRegister;
use App\Services\CashRegisterService;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CashRegisterController extends ApiController
{
    private CashRegisterService $cashRegisterService;
    public function __construct(CashRegisterService $cashRegisterService)
    {
        $this->cashRegisterService = $cashRegisterService;
        $this->model = new CashRegister();
        parent::__construct();
    }

    public function getCashRegisters()
    {
        $cashRegisters = $this->cashRegisterService->getCashRegisters();

        if (! assert($cashRegisters instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return $this->successResponse($cashRegisters);
    }

    public function openCashRegister(OpenCashRegisterRequest $request) 
    {
        $content = $request->all();
        $content['openedBy'] = $request->user()->id;
        $content['openingTime'] =  (new DateTime('now'))->format('Y-m-d H:i:s');

        $cashRegister = $this->cashRegisterService->openCashRegister($content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'cashRegister' => $cashRegister
        ]);
    }

    public function closeCashRegister(CloseCashRegisterRequest $request, int $cashRegisterId)
    {
        $cashRegister = $this->cashRegisterService->getCashRegister($cashRegisterId);

        if(! isset($cashRegister)){
            throw new ObjectNotFountException($cashRegisterId, 'CashRegister');
        }

        $content = $request->all();
        $content['closedBy'] = $request->user()->id;
        $content['closingTime'] =  (new DateTime('now'))->format('Y-m-d H:i:s');

        $updatedCashRegister = $this->cashRegisterService->closeCashRegister($cashRegisterId, $content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'cashRegister' => $updatedCashRegister,
            'message' => 'CashRegister successfully updated.'
        ]);
    }
}
