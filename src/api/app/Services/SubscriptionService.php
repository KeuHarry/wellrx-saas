<?php

namespace App\Services;

use App\Models\Tenant;
use App\Repositories\TenantRepository;
use Str;
use Stripe\StripeClient;

class SubscriptionService
{
    public function __construct(private TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function registerTenant(
        string $pharmacyName,
        string $regNumber,
        string $account,
        string $email,
        string $country,
        string $address,
        string $phoneNumber
    ): Tenant {
        $identifier = Str::uuid7()->toString();

        return $this->tenantRepository->create($pharmacyName, $regNumber, $account, $email, $country, $address, $phoneNumber, $identifier);
    }

    public function checkTenantByAccount(string $account): ?Tenant
    {
        return $this->tenantRepository->findByAccount($account);
    }

    public function checkTenantByIdentifier(string $identifier): ?Tenant
    {
        return $this->tenantRepository->findByIdentifier($identifier);
    }

    public function createSession(string $priceId): string
    {
        $stripe = new StripeClient('sk_test_51GbM2jLt3tZinhYdhdKDkbbwPawY6aJXMDuoI3Q6PyDWSKWN4Hl93PJrv8bFV5F1PHlbR0h82VcVJQAzcGuAIKIQ00v7wGVPqe');

        $session = $stripe->checkout->sessions->create([
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'http://wellrx.localhost/sign-up',
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
        ]);

        return $session->id;
    }
}
