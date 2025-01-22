<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AssignPermissionRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\RoleResource;
use App\Models\Tenant\Permission;
use App\Models\Tenant\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleController extends ApiController
{
    public function __construct(private RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->model = new Role();
        parent::__construct($roleService);
    }

    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->getById($id);
        $role = new RoleResource($role);
        return $this->successResponse($role);
    }

    public function createRole(RoleRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function updateRole(RoleRequest $request, int $roleId): JsonResponse
    {
        return $this->update($roleId, $request);
    }

    public function getRoles(SearchRequest $request): JsonResponse
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $roles = $this->roleService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        return $this->successResponse($roles, 'Roles successfully retrieved!', Response::HTTP_OK, $this->roleService->getModelCount());
    }

    public function getPermissions(): JsonResponse
    {
        $permissions = $this->roleService->getAllPermissions();
        return $this->successResponse($permissions, 'Permissions successfully retrieved!', Response::HTTP_OK, $this->roleService->getPermissionsCount());
    }

    public function assign(AssignPermissionRequest $request): JsonResponse
    {
        $content = $request->safe();
        $content = $content->toArray();
        $result = $this->roleService->assignPermissions($content['roleId'], $content['permissions']);

        return $this->successResponse($result, 'permissions successfully updated!');
    }
}
