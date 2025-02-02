<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\InventoryType;
use App\Http\Controllers\ApiController;
use App\Http\Requests\InventoryOutRequest;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Inventory;
use App\Services\InventoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class InventoryController extends ApiController
{
    private InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        $this->model = new Inventory();
        parent::__construct($inventoryService);
    }

    public function getInventories(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $type = intval($request->query->get('type'));

            $inventories = $this->inventoryService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy,
                $type
            );

            return $this->successResponse(
                $inventories, 
                'Inventories successfully retrieved!',
                Response::HTTP_OK,
                $this->inventoryService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }


    public function createInventory(InventoryRequest $request): JsonResponse
    {
        return $this->bulkStore($request);
    }

    public function inventoryOut(InventoryOutRequest $request): JsonResponse
    {
        $content = $request->safe()->toArray();
        $inventory = $this->inventoryService->getById(intval($content['inventoryId']));
        assert($inventory instanceof Inventory);

        if ($inventory->type !== InventoryType::IN->value) {
            return $this->errorResponse([], 'inventory type must be in');
        }

        $content['productId'] = $inventory->product->id;
        $content['expiryDate'] = $inventory->expiry_date;
        $content['batchId'] = $inventory->batch_id;
        $content['acqCost'] = $inventory->acq_cost;
        $content['wholesaleCost'] = $inventory->wholesale_cost;
        $content['retailCost'] = $inventory->retail_cost;
        $content['unitCost'] = $inventory->unit_cost;
        $content['subTotal'] = $content['quantity'] * $content['acqCost'];
        $content['supplierId'] = $inventory->supplier_id;
        $newInventoryEntry = $this->inventoryService->create($content);

        return $this->successResponse($newInventoryEntry, 'Inventory out completed!');
    }

    public function updateInventory(InventoryRequest $request, int $inventoryId)
    {
        return $this->update($inventoryId, $request);
    }
}
