<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class Insurer extends BaseModel
{
    use Notifiable;

    public $validation_rules = [
        'name' => 'required|unique:App\Models\Tenant\Customer,name',
        'tax_payer_number' => 'required|string',
        'registration_number' => 'required|string',
        'credit_limit' => 'numeric|min:0',
        'balance' => 'numeric|min:0',
        'deposit' => 'numeric|min:0',
        'address' => 'string|nullable',
        'neighborhood' => 'string|nullable',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'insurers';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        //'delayed' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tax_payer_number',
        'registration_number',
        'credit_limit',
        'balance',
        'deposit',
        'address',
        'neighborhood',
    ];

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['taxPayerNumber'] = $this->tax_payer_number;
        $json['registrationNumber'] = $this->registration_number;
        $json['creditLimit'] = $this->credit_limit;
        $json['balance'] = $this->balance;
        $json['deposit'] = $this->deposit;
        $json['address'] = $this->address;
        $json['neighborhood'] = $this->neighborhood;

        return $json;
    }
}
