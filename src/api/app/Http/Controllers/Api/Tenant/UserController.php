<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\UserNotFountException;
use App\Http\Controllers\ApiController;
use App\Models\Tenant\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->model = new User();
        parent::__construct();
    }

    public function createUser(Request $request) 
    {
        $content = $request->all();

        $validator = Validator::make($content, [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'username' => 'string',
            'email' => 'required|email|unique:users',
            'roleId' => 'nullable|integer'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $user = $this->userService->createUser(
            $content['firstName'],
            $content['lastName'],
            $content['username'],
            $content['email'],
            $content['roleId']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'user' => $user
        ]);
    }

    public function getUser(int $userId)
    {
        $user = $this->userService->getUser($userId);

        if(! isset($user)){
            throw new UserNotFountException($userId);
        }
        return $this->successResponse($user);
    }

    public function getUsers()
    {
        $users = $this->userService->getUsers();

        if (! assert($users instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return $this->successResponse($users);
    }

    public function deleteUser(int $userId)
    {
        $user = $this->userService->getUser($userId);

        if(! isset($user)){
            throw new UserNotFountException($userId);
        }

        if($this->userService->deleteUser($user->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'user' => $userId,
                'message' => 'User successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUser(Request $request, string $userId)
    {
        $user = $this->userService->getUser($userId);

        if(! isset($user)){
            throw new UserNotFountException($userId);
        }

        $content = $request->all();

        $validator = Validator::make($content, [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'username' => 'string',
            'email' => 'required|email',
            'roleId' => 'nullable|integer'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $updatedUser = $this->userService->updateUser(
            $user,
            $content['firstName'],
            $content['lastName'],
            $content['username'],
            $content['email'],
            $content['roleId']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'user' => $updatedUser->id
        ]);
    }
}
