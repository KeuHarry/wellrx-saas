<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class Fee extends BaseModel
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fees';

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
        'fee_code',
        'name',
        'value',
        'type',
        'tag',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(
            function ($item) {
                /* $item->modifying_user_id = self::getCurrentUser()->id; */
                if (empty($item->fee_code)) {
                    $item->fee_code = 'FT'.str_pad(strval(self::getNextId()), 4, '0', STR_PAD_LEFT);
                }

                //return self::beforeSave($item);
            }
        );
    }

    public static function getNextId(): mixed
    {
        $lastFee = Fee::orderBy('id', 'desc')->first();

        return $lastFee ? $lastFee->id + 1 : 1;
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['feeCode'] = $this->fee_code;
        $json['name'] = $this->name;
        $json['cost'] = $this->value;
        $json['type'] = $this->type;
        $json['tag'] = $this->tag;
        $json['description'] = $this->description;

        return $json;
    }
}
