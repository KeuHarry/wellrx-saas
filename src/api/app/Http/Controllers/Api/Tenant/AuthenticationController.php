<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\Tenant\User;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(AuthRequest $request): JsonResponse
    {
        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid credentials',
                //'permissions' => $permissions,
            ], 401);
        }

        // $request->session()->regenerate();
        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => new AuthUserResource($user),
            'token' => $token,
        ], 200);

        //dd($request->password);
        //dd(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]));
        /*if( !Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1]) ){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Invalid credentials',
            ], 401);
        }*/

        // if( isset($request->email) ){
        //     $user = User::where('email', $request->email)->first();
        // }

        //$token = $user->createToken('authToken')->plainTextToken;

        //get the user permissions
        /*$permissions = UserRole::join('role_permissions','user_roles.role_id', '=', 'role_permissions.role_id')
                                ->join('permissions','permissions.id','=','role_permissions.permission_id')
                                ->where('user_roles.user_id', $user->id)
                                ->select('permissions.mode')
                                ->get();*/
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User successfully signed out',
        ], 200);
    }

    public function generatePin()
    {
        $users = User::all();
        $users_count = count($users);
        $tempArray = [];
        for ($i = 0; $i < $users_count; $i++) {
            array_push($tempArray, $users[0]['attributes']['pin']);
        }
        $newPin = '';
        do {
            $newPin = mt_rand(10000, 99999).'';
        } while (in_array($newPin, $tempArray));

        return response()->json(['pin' => $newPin], 200);
    }
}
