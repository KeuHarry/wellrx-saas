<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\TenantResource;
use App\Services\SubscriptionService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

//use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private UserService $userService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->userService = $userService;
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $data = $request->all();

        $tenant = $this->subscriptionService->registerTenant(
            $data['pharmacyName'],
            $data['registrationNumber'],
            $data['account'],
            $data['email'],
            $data['pharmacyCountry'],
            $data['pharmacyAddress'],
            $data['phoneNumber']
        );

        if (! isset($tenant->id)) {
            return response()->json(
                [
                    'error' => 'Account has not been created',
                ], 500
            );
        }

        $tenant->run(function () use ($tenant, $data) {
            $this->userService->createDefaultUser('admin', 'admin', 'administrator', $tenant->email, $data['password']);
        });

        return response()->json(
            [
                'success' => 'Account created successfully',
                'tenant' => new TenantResource($tenant),
            ], 200
        );
    }

    public function checkIfTenantExists(string $identifier): JsonResponse
    {
        $tenant = $this->subscriptionService->checkTenantByIdentifier($identifier);

        if (! $tenant) {
            return response()->json([
                'success' => false,
                'tenant' => $tenant,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tenant' => new TenantResource($tenant),
        ], 200);
    }

    public function checkTenantByAccount(string $account): JsonResponse
    {
        $tenant = $this->subscriptionService->checkTenantByAccount($account);

        if (! $tenant) {
            return response()->json([
                'success' => false,
                'tenant' => $tenant,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tenant' => new TenantResource($tenant),
        ], 200);
    }
}
