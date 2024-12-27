<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;

class Supplier extends BaseModel
{
    use Notifiable;

    public $validation_rules = [
        'name' => 'required|unique:App\Models\Tenant\Supplier,name'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';
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
        'code', 
        'name',
        'address',
        'contact',
        'email',
        'postal_code',
        'phone',
        'fax',
        'city'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            self::beforeSave($item);
            return true;
        });

        static::updating(function ($item) {
            self::beforeSave($item);
            return true;
        });

        static::saving(function ($item) {
            //$item->modifying_user_id = Product::getCurrentUserId();
            if(empty($item->code)) {
                $item->code = 'SUP'.str_pad(self::getNextId(), 4, 0, STR_PAD_LEFT);
            }
            return self::beforeSave($item);
        });
    }

    /**
     * Function to do smth before save
     */
    static public function beforeSave($item)
    {
        if (!isset($item->id) || (isset($item->id) && empty($item->id))) {
            $lastItem = self::orderBy('id','desc')->take(1)->get()->first();
            if ($lastItem) {
                $item->id = $lastItem->id+1;
            } else {
                $item->id = 1;
            }
        }
        //$item->modifying_user_id = Product::getCurrentUserId();
        return true;
    }

    static public function getNextId()
    {
        $last = Supplier::orderBy('id','desc')->take(1)->get()->first();
        return $last ? $last->id + 1 : 1;
    }


    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['email'] = $this->email;
        $json['address'] = $this->address;
        $json['city'] = $this->city;
        $json['contact'] = $this->contact;
        $json['phone'] = $this->phone;
        $json['fax'] = $this->fax;
        $json['postalCode'] = $this->postal_code;

        return $json;
    }
}
