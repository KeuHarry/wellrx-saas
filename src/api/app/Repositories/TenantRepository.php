<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Models\Tenant\User;

class TenantRepository
{
    public function findByAccount(string $account): ?Tenant
    {
        return Tenant::where('account', $account)->whereNull('deleted_at')->first();
    }

    public function findByIdentifier(string $identifier): ?Tenant
    {
        return Tenant::where('identifier', $identifier)->whereNull('deleted_at')->first();
    }

    public function create(
        string $pharmacyName, 
        string $registrationNumber, 
        string $account, 
        string $email, 
        string $country, 
        string $address, 
        string $phoneNumber,
        string $identifier
    ): Tenant
    {
        $tenant = new Tenant();
        $tenant->setPharmacyName($pharmacyName);
        $tenant->setRegistrationNumber($registrationNumber);
        $tenant->setAccount($account);
        $tenant->setEmail($email);
        $tenant->setCountry($country);
        $tenant->setPharmacyAddress($address);
        $tenant->setPhoneNumber($phoneNumber);
        $tenant->setIdentifier($identifier);
        $tenant->save();

        return $tenant;
    }

}
