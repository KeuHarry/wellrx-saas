<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\ObjectNotFoundException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\ShelfRequest;
use App\Models\Tenant\Shelf;
use App\Services\ShelfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ShelfController extends ApiController
{
    private ShelfService $shelfService;
    public function __construct(ShelfService $shelfService)
    {
        $this->shelfService = $shelfService;
        $this->model = new Shelf();
        parent::__construct();
    }

    public function createShelf(ShelfRequest $request) 
    {
        $content = $request->safe();

        $shelf = $this->shelfService->createShelf(
            $content['name'],
            $content['description']
        );

        return new JsonResponse([
            'succes' => true,
            'message' => 'Shelf created successfully.',
            'data' => $shelf,
            'errors' => null
        ]);
    }

    public function getShelf(int $shelfId)
    {
        $shelf = $this->shelfService->getShelf($shelfId);

        return $this->successResponse($shelf);
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

        return $this->successResponse($shelves);
    }

    public function deleteShelf(int $shelfId)
    {
        $shelf = $this->shelfService->getShelf($shelfId);

        if(! isset($shelf)){
            throw new ObjectNotFoundException($shelfId, 'Shelf');
        }

        if($this->shelfService->deleteShelf($shelf->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'shelf' => $shelfId,
                'message' => 'Shelf successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateShelf(ShelfRequest $request, int $shelfId)
    {
        $content = $request->safe();

        $updatedShelf = $this->shelfService->updateShelf(
            $shelfId,
            $content['name'],
            $content['description']
        );

        return new JsonResponse([
            'success' => true,
            'message' => 'Shelf successfully updated.',
            'data' => $updatedShelf,
            'errors' => null
        ]);
    }
}
