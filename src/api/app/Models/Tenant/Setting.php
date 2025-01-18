<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;
use Str;

class Setting extends BaseModel
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    // Disable auto-incrementing primary key
    public $incrementing = false;

    // Specify the primary key type
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];

    // Automatically generate UUID when creating a new model instance
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['key'] = $this->key;
        $json['value'] = $this->value;

        return $json;
    }
}
