<?php

namespace App\Http\Controllers;

use Countable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public object $model;

    public object $query;

    public array $query_with = [];

    public int $user_field;

    public bool $is_success_request;

    public object $item;

    public array $_validators;

    public mixed $current_user;

    public function __construct()
    {
        //$this->current_user = auth()->user();
        $this->query = $this->getNewQuery();
        if (! empty($this->query_with)) {
            $this->query->with($this->query_with);
        }
        $this->_validators = $this->model->getValidationRules();
    }

    public function getNewQuery(): object
    {
        return $this->model->newQuery();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //$this->applyRequestFilters();

        //get all count
        $total = $this->query->count();

        /* $this->applyRequestOrder();
        $this->applyRequestLimit(); */

        $items = $this->query->get();

        $output = [
            'total' => $total,
            'data' => [],
        ];

        foreach ($items as $item) {
            $output['data'][] = $item->getItemData();
        }

        return $this->successResponse($output);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = [];
        if ($this->user_field) {
            $data = [$this->user_field => $this->current_user->id];
        }

        $validator = Validator::make($request->all() + $data, $this->getValidators());

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    'errors' => $validator->errors()->all(['field' => ':key', 'message' => ':message']),
                ]
            );
        }

        $requestData = $request->all();
        foreach ($requestData as $name => $value) {
            foreach ($this->model->getFillable() as $fillable) {
                if ($fillable == $name) {
                    $data[$name] = $value;
                }
            }
        }

        if (! $item = $this->model->create($data)) {
            return $this->errorResponse(
                [
                    'errors' => 'Internal Error. No item created',
                ]
            );
        } else {
            $this->setItem($item);

            return $this->successResponse($this->getSuccessStoreResponseData());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $item = $this->query->where('id', $id)->first();

        if (! $item) {
            return $this->errorResponse(
                [
                    'errors' => 'Item not found',
                ]
            );
        } else {
            $this->setItem($item);

            return $this->successResponse($item->getItemData(true));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->getValidators($id));

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    'errors' => $validator->errors()->all(['field' => ':key', 'message' => ':message']),
                ]
            );
        }

        if (! $item = $this->query->where('id', $id)->first()) {
            return $this->errorResponse(
                [
                    'errors' => 'Internal Error. Item not found',
                ]
            );
        }

        $requestData = $request->all();
        $data = [];
        foreach ($requestData as $name => $value) {
            foreach ($this->model->getFillable() as $fillable) {
                if ($fillable == $name) {
                    $data[$name] = $value;
                }
            }
        }

        $update = $item->update($data);

        if (! $update) {
            return $this->errorResponse(
                [
                    'errors' => 'Internal Error. Item not updated',
                ]
            );
        } else {
            $this->setItem($item);

            return $this->successResponse($this->getSuccessUpdateResponseData());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (! $this->model->destroy($id)) {
            return $this->errorResponse(
                [
                    'errors' => 'Internal Error. Item not deleted',
                ]
            );
        } else {
            return $this->successResponse(
                [
                    'message' => 'Successfully deleted!',
                ]
            );
        }
    }

    protected function setItem(object $item): void
    {
        $this->item = $item;
    }

    protected function getValidators(?int $id = null): array
    {
        if ($id) {
            foreach ($this->_validators as $field => $validator) {
                if (strpos($validator, 'unique') !== false) {
                    $this->_validators[$field] .= ','.$id;
                }
            }
        }

        return $this->_validators;
    }

    /**
     * Get success store response data.
     */
    public function getSuccessStoreResponseData(): array
    {
        return [
            'message' => 'Successfully added!',
            'item' => $this->item->getItemData(true),
        ];
    }

    /**
     * Get success update response data.
     */
    public function getSuccessUpdateResponseData(): array
    {
        return [
            'message' => 'Successfully updated!',
            'item' => $this->item->getItemData(true),
        ];
    }

    /**
     * Returns error json response
     */
    public function errorResponse(mixed $data): JsonResponse
    {
        $this->is_success_request = false;

        return response()->json(
            [
                'success' => false,
                'data' => $data,
            ], 422
        );
    }

    /**
     * Returns success json response
     */
    public function successResponse(mixed $data): JsonResponse
    {
        $this->is_success_request = true;

        return response()->json(
            [
                'success' => true,
                'total' => $data instanceof Countable || is_array($data) ? count($data) : 1,
                'data' => $data,
                'errors' => null,
            ], 200
        );
    }
}
