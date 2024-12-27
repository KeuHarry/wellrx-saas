<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Category extends BaseModel
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
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
        'markup',
        'description'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(
            function ($item) {
                /* $item->modifying_user_id = self::getCurrentUser()->id; */
                return self::beforeSave($item);
            }
        );

        static::saving(
            function ($item) {
                /* $item->modifying_user_id = self::getCurrentUser()->id; */
                if(empty($item->code)) {
                    $item->code = 'CAT'.str_pad(strval(self::getNextId()), 4, "0", STR_PAD_LEFT);
                }
                return self::beforeSave($item);
            }
        );
    }

    /**
     * Function to do smth before save
     */
    static public function beforeSave(Category $item): bool
    {
        return true;
    }

    static public function getNextId(): mixed
    {
        $lastCat = Category::orderBy('id', 'desc')->first();
        return $lastCat ? $lastCat->id + 1 : 1;
    }

    /* Get all products related to category */
    public function products(): HasMany
    {
        return $this->hasMany('App\Models\Tenant\Product', 'product_category_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['code'] = $this->code;
        $json['name'] = $this->name;
        $json['markup'] = $this->markup;
        $json['description'] = $this->description;
        $json['productCount'] = count($this->products);

        return $json;
    }
}
