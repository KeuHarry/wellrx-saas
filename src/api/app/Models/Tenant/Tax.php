<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class Tax extends BaseModel
{
    use Notifiable;

    public $validation_rules = [
        'label' => 'required|max:20|unique:App\Models\Tenant\Tax,label',
        'cost' => 'required|numeric',
        'description' => 'nullable',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxes';

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
        'label',
        'cost',
        'description',
    ];

    public function getItemLabel()
    {
        return 'Tax';
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['label'] = $this->label;
        $json['cost'] = $this->cost;
        $json['description'] = $this->description;

        return $json;
    }
}
