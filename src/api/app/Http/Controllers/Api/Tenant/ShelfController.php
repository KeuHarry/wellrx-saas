<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\ShelfRequest;
use App\Http\Requests\UpdateShelfRequest;
use App\Models\Tenant\Shelf;
use App\Services\ShelfService;
use Illuminate\Http\JsonResponse;

class ShelfController extends ApiController
{
    private ShelfService $shelfService;

    public function __construct(ShelfService $shelfService)
    {
        $this->shelfService = $shelfService;
        $this->model = new Shelf();
        parent::__construct($shelfService);
    }

    public function createShelf(ShelfRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateShelf(UpdateShelfRequest $request, int $shelfId): JsonResponse
    {
        return $this->update($shelfId, $request);
    }

    public function getShelves(SearchRequest $request): JsonResponse
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $shelves = $this->shelfService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        return $this->successResponse($shelves, 'Shelves successfully retrieved!');
    }
}
