<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class LabelCode extends BaseModel
{
    use Notifiable;

    public $validation_rules = [
        'code' => 'required|max:225',
        'label' => 'required|max:225'
    ];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'label_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'label'
    ];

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['code'] = $this->code;
        $json['label'] = $this->label;

        return $json;
    }
}
