<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Countable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    protected Model $model;

    public object $query;

    public array $query_with = [];

    public int $user_field;

    public object $item;

    public array $_validators;

    public mixed $currentUser;

    public function __construct(private ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->currentUser = Auth::user();
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
     * Create a resource.
     */
    public function store(FormRequest $request): JsonResponse
    {
        try {
            $content = $request->safe();
            $result = $this->apiService->create($content->toArray());

            return $this->successResponse($result, class_basename($this->model).' successfully created');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create '.class_basename($this->model));
        }
    }

    /**
     * Get a resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $result = $this->apiService->getById($id);

            return $this->successResponse($result, class_basename($this->model).' successfully retrieved');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), 'Not found', Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve the resource');
        }
    }

    /**
     * Update a specified resource.
     */
    public function update(int $id, FormRequest $request): JsonResponse
    {
        try {
            $content = $request->safe();
            $result = $this->apiService->update($id, $content->toArray());
            return $this->successResponse($result, class_basename($this->model).' successfully updated');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), 'Resource not found', Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to update the resource');
        }
    }

    /**
     * Delete a specified resource.
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $result = $this->apiService->delete($id);
            return $this->successResponse($result, class_basename($this->model).' successfully deleted');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), 'Resource not found', Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to delete '.$id);
        }
    }

    /**
     * Returns error json response
     */
    public function errorResponse(mixed $errors, $message = 'An error occurred', $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Returns success json response
     */
    public function successResponse(mixed $data, $message = '', $statusCode = 200, ?int $total = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'total' => $total,
            'data' => $data,
        ], $statusCode);
    }
}
