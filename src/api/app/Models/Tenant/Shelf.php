<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Shelf extends BaseModel
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shelves';

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
        'shelf_code',
        'name',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(
            function ($item) {
                //$item->modifying_user_id = self::getCurrentUser()->id;
                return self::beforeSave($item);
            }
        );

        static::saving(
            function ($item) {
                //$item->modifying_user_id = self::getCurrentUser()->id;
                if (empty($item->shelf_code)) {
                    $item->shelf_code = 'SH'.str_pad(strval(self::getNextId()), 4, '0', STR_PAD_LEFT);
                }

                return self::beforeSave($item);
            }
        );
    }

    public static function getNextId(): mixed
    {
        $lastCat = Shelf::orderBy('id', 'desc')->first();

        return $lastCat ? $lastCat->id + 1 : 1;
    }

    /**
     * Function to do smth before save
     */
    public static function beforeSave(Shelf $item): bool
    {
        return true;
    }

    /* Get all products related to shelf */
    public function products(): HasMany
    {
        return $this->hasMany('App\Models\Tenant\Product', 'shelf_id');
    }

    public function getProductsCount(): int
    {
        if (! array_key_exists('products', $this->relations)) {
            $this->load('products');
        }

        $related = $this->getRelation('products');

        return ($related) ? (int) count($related) : 0;
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['shelfCode'] = $this->shelf_code;
        $json['name'] = $this->name;
        $json['description'] = $this->description;
        $json['productCount'] = count($this->products);

        return $json;
    }
}
