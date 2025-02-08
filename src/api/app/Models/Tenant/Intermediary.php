<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Intermediary extends BaseModel
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'intermediaries';

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
        'phone',
        'address',
    ];

    /* Get all patients related to intermediary */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'intermediary_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['phone'] = $this->phone;
        $json['address'] = $this->address;

        return $json;
    }
}
