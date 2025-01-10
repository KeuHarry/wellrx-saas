<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Product extends BaseModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Product $item) {
            self::beforeCreating($item);
            self::beforeSave($item);

            return true;
        });
        static::updating(function (Product $item) {
            self::beforeSave($item);

            return true;
        });
    }

    public $validation_rules = [
        'label' => 'required|max:255|unique:product,label',
        'ndc' => 'max:255',
        'product_category_id' => 'required',
        'shelf_id' => 'required',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'is_prescription',
        'is_generic',
        'generic_product_id',
        'barcode',
        'cip_code',
        'ndc',
        'card_number',
        'markup',
        'tax_id',
        'wholesale_cost',
        'retail_cost',
        'description',
        'qoh',
        'min_quantity',
        'max_quantity',
        'is_active',
        'retail_cost',
        'acq_cost',
        'shelf_id',
        'product_category_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public $relations_data = ['cip_codes'];

    /**
     * Mutator for nullable foreign keys
     */
    /*public function setTaxIdAttribute($value) {
        $this->attributes['tax_id'] = $value ?: null;
    }

    public function setGenericProductIdAttribute($value) {
        $this->attributes['generic_product_id'] = $value ?: null;
    }*/

    /**
     * Get the generic.
     */
    public function generic()
    {
        return $this->belongsTo('App\Models\Product', 'generic_product_id');
    }

    /**
     * Get the category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category_id');
    }

    /**
     * Get the shelf.
     */
    public function shelf()
    {
        return $this->belongsTo(Shelf::class, 'shelf_id');
    }

    /**
     * Get the inventory items.
     */
    public function inventory_items()
    {
        return $this->hasMany('App\Models\Inventory', 'product_id');
    }

    /**
     * Get the cip codes.
     */
    /*public function cip_codes()
    {
        return $this->hasMany('App\Models\CipCode', 'product_id');
    }*/

    /**
     * Get the cip codes data.
     */
    /*public function cip_codes_get_data()
    {
        $cip_codes = [];
        foreach ($this->cip_codes as $item) {
            $cip_codes[] = $item->getItemData();
        }
        return $cip_codes;
    }*/

    /**
     * Get the label codes data.
     */
    /*public function label_codes_get_data()
    {
        $label_codes = [];
        foreach ($this->label_codes as $item) {
            $label_codes[] = $item->label_code->getItemData();
        }
        return $label_codes;
    }*/

    /**
     * Get the tax.
     */
    public function tax()
    {
        return $this->belongsTo('App\Models\Tax', 'tax_id');
    }

    /* Get all inventory records related to product */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    /**
     * Function to do smth before save
     */
    public static function beforeCreating($item)
    {
        return true;
    }

    /**
     * Function to update some fields before save
     */
    public static function beforeSave($item)
    {
        //$item->created_by = self::getCurrentUserId();
        if (empty($item->product_code)) {
            $item->product_code = 'PD'.str_pad(self::getNextId(), 5, 0, STR_PAD_LEFT);
        }
        /*if(empty($item->stock_number)) {
            $item->stock_number = self::getNextStockNumber();
        }*/
    }

    /**
     * Get product qoh at a particular time
     */
    /*public function getQOHBefore($end_date){
        return Inventory::where('product_id', $this->id)->where('updated_at', '<', $end_date)->sum('quantity_on');
    }*/

    /*static public function getQOHBeforeByProduct($productid, $end_date){
        return Inventory::where('product_id', $productid)->where('updated_at', '<', $end_date)->sum('quantity_on');
    }*/
    /**
     * Get product qoh at a particular time
     */
    /*public function getQOHAfter($end_date){
        $end_date = date("Y-m-d H:i:s", strtotime('+1 hour',strtotime($end_date)));
        return Inventory::where('product_id', $this->id)->where('updated_at', '<=', $end_date)->sum('quantity_on');
    }*/

    public static function getCurrentUserId()
    {
        $currentUser = Auth::user();

        return $currentUser ? $currentUser->id : 0;
    }

    public static function getNextId()
    {
        $lastProd = Product::orderBy('id', 'desc')->take(1)->get()->first();

        return $lastProd ? $lastProd->id + 1 : 1;
    }

    public static function getNextStockNumber()
    {
        $lastItem = Product::orderBy('id', 'desc')->take(1)->get()->first();

        return $lastItem ? $lastItem->stock_number + 1 : 1;
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['productCode'] = $this->product_code;
        $json['stockNumber'] = $this->stock_number;
        $json['label'] = $this->label;
        $json['pkgSize'] = $this->pkg_size;
        $json['isPrescription'] = $this->is_prescription;
        $json['isGeneric'] = $this->is_generic;
        $json['barcode'] = $this->barcode;
        $json['cipCode'] = $this->cip_code;
        $json['ndc'] = $this->ndc;
        $json['cardNumber'] = $this->card_number;
        $json['markup'] = $this->markup;
        $json['acqCost'] = $this->acq_cost;
        $json['wholesaleCost'] = $this->wholesale_cost;
        $json['retailCost'] = $this->retail_cost;
        $json['unitCost'] = $this->unit_cost;
        $json['description'] = $this->description;
        $json['qoh'] = $this->qoh;
        $json['minQuantity'] = $this->min_quantity;
        $json['maxQuantity'] = $this->max_quantity;
        $json['isActive'] = $this->is_active;
        $json['shelfId'] = $this->shelf_id;
        $json['productCategoryId'] = $this->product_category_id;
        $json['taxId'] = $this->tax_id;

        return $json;
    }
}
