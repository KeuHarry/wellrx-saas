<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class Customer extends BaseModel
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

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
        'email',
        'credit_limit',
        'purchased_total',
        'balance',
        'deposit',
        'phone',
        'address',
        'city',
    ];

    public function addDeposit(float $amount)
    {
        $deposit = round($this->deposit + $amount, 2);
        $this->update(['deposit' => $deposit]);
    }

    public function reduceBalance(float $deposit)
    {
        $balance = round($this->balance - $deposit, 2);
        $this->update(['balance' => $balance]);
    }

    public function addBalance(float $amount)
    {
        $balance = round($this->balance + $amount, 2);
        $this->update(['balance' => $balance]);
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['email'] = $this->email;
        $json['creditLimit'] = $this->credit_limit;
        $json['purchasedTotal'] = $this->purchased_total;
        $json['balance'] = $this->balance;
        $json['deposit'] = $this->deposit;
        $json['phone'] = $this->phone;
        $json['address'] = $this->address;
        $json['city'] = $this->city;

        return $json;
    }
}
