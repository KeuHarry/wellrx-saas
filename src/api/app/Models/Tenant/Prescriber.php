<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class Prescriber extends BaseModel
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescribers';

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
        'first_name',
        'last_name',
        'registration_number',
        'phone',
        'address'
    ];

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['firstName'] = $this->first_name;
        $json['lastName'] = $this->last_name;
        $json['registrationNumber'] = $this->registration_number;
        $json['phone'] = $this->phone;
        $json['address'] = $this->address;

        return $json;
    }
}
