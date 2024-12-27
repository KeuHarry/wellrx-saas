<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Inventory\InventoryRequest;
use App\Models\Tenant\Inventory;
use App\Services\InventoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InventoryController extends ApiController
{
    private InventoryService $inventoryService;
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        $this->model = new Inventory();
        parent::__construct();
    }

    public function createInventory(InventoryRequest $request) 
    {
        $content = $request->all();
        $content['createdBy'] = $request->user()->id;

        $inventory = $this->inventoryService->createInventory($content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'inventory' => $inventory
        ]);
    }

    public function getInventory(int $inventoryId)
    {
        $inventory = $this->inventoryService->getInventory($inventoryId);

        if(! isset($inventory)){
            throw new ObjectNotFountException($inventoryId, 'Inventory');
        }
        return $this->successResponse($inventory);
    }

    public function getInventories()
    {
        $inventories = $this->inventoryService->getInventories();

        if (! assert($inventories instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return $this->successResponse($inventories);
    }

    public function deleteInventory(int $inventoryId)
    {
        $inventory = $this->inventoryService->getInventory($inventoryId);

        if(! isset($inventory)){
            throw new ObjectNotFountException($inventoryId, 'Inventory');
        }

        if($this->inventoryService->deleteInventory($inventory->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'inventory' => $inventoryId,
                'message' => 'Inventory successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
