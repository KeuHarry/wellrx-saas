<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

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
        'markup',
        'fee_id',
        'acq_cost',
        'wholesale_cost',
        'retail_cost',
        'unit_cost',
        'description',
        'qoh',
        'min_quantity',
        'max_quantity',
        'is_active',
        'shelf_id',
        'product_category_id',
        'is_decomposable',
        'pkg_size',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_prescription' => 'boolean',
        'is_generic' => 'boolean',
        'is_active' => 'boolean',
        'is_decomposable' => 'boolean',
    ];

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
        return $this->belongsTo(Product::class, 'generic_product_id');
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
    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    /* Get all inventory records related to product */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'product_id');
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
        $json['label'] = $this->label;
        $json['pkgSize'] = $this->pkg_size;
        $json['isPrescription'] = $this->is_prescription;
        $json['isGeneric'] = $this->is_generic;
        $json['barcode'] = $this->barcode;
        $json['cipCode'] = $this->cip_code;
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
        $json['shelf'] = [
            'id' => $this->shelf->id,
            'name' => $this->shelf->name,
        ];
        $json['category'] = [
            'id' => $this->category->id,
            'name' => $this->category->name
        ];
        $json['fee'] = $this->fee ? [
            'id' => $this->fee->id,
            'name' => $this->fee->name,
            'value' => $this->fee->value,
        ] : null;
        $json['generic'] = $this->generic ? [
            'id' => $this->generic->id,
            'name' => $this->generic->label
        ] : null;

        return $json;
    }
}
