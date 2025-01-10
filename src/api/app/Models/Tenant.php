<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use Billable, HasDatabase, HasDomains, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tenants';

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): string
    {
        return $this->identifier = $identifier;
    }

    public function setPharmacyName(string $pharmacyName): void
    {
        $this->pharmacy_name = $pharmacyName;
    }

    public function setRegistrationNumber(string $regNumber): void
    {
        $this->registration_number = $regNumber;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    public function setPharmacyAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phone_number = $phoneNumber;
    }

    public function setDescription(?string $description = null): void
    {
        $this->description = $description;
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'pharmacy_name',
            'registration_number',
            'identifier',
            'account',
            'email',
            'country',
            'address',
            'phone_number',
            'deleted_at',
        ];
    }
}
