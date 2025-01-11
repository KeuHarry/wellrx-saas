<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\Tenant\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->model = new User();
        parent::__construct($userService);
    }

    public function createUser(UserRequest $request)
    {
        try {
            $content = $request->safe();
            $content = $content->toArray();
            $content['password'] = $content['username'].'@123';
            $result = $this->userService->create($content);

            return $this->successResponse($result, class_basename($this->model).' successfully created');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create '.class_basename($this->model));
        }
    }

    public function updateUser(UserRequest $request, string $userId): JsonResponse
    {
        return $this->update($userId, $request);
    }

    public function getUsers(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $users = $this->userService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse($users, class_basename($this->model).' successfully retrieved!', total: $this->userService->getModelCount());
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }  
}
