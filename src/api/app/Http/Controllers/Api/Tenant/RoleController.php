<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Models\Tenant\Role;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends ApiController
{
    private RoleService $roleService;
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->model = new Role();
        parent::__construct();
    }

    public function createRole(Request $request) 
    {
        $content = $request->all();

        $validator = Validator::make($content, [
            'label' => 'required|string',
            'description' => 'string'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $role = $this->roleService->createRole(
            $content['label'],
            $content['description']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'role' => $role
        ]);
    }

    public function getRole(int $roleId)
    {
        $role = $this->roleService->getRole($roleId);

        if(! isset($role)){
            throw new ObjectNotFountException($roleId, 'Role');
        }
        return $this->successResponse($role);
    }

    public function getRoles()
    {
        $roles = $this->roleService->getRoles();

        if (! assert($roles instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return $this->successResponse($roles);
    }

    public function deleteRole(int $roleId)
    {
        $role = $this->roleService->getRole($roleId);

        if(! isset($role)){
            throw new ObjectNotFountException($roleId, 'Role');
        }

        if($this->roleService->deleteRole($role->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'role' => $roleId,
                'message' => 'Role successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRole(Request $request, string $roleId)
    {
        $role = $this->roleService->getRole($roleId);

        if(! isset($role)){
            throw new ObjectNotFountException($roleId, 'Role');
        }

        $content = $request->all();

        $validator = Validator::make($content, [
            'label' => 'required|string',
            'description' => 'string'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $updatedRole = $this->roleService->updateRole(
            $role,
            $content['label'],
            $content['description']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'role' => $updatedRole
        ]);
    }
}
