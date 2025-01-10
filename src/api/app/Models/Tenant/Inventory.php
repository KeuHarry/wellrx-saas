<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Inventory extends BaseModel
{
    use Notifiable;

    const OUT = 0;

    const IN = 1;

    const SELL = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventory';

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
        'product_id',
        'quantity',
        'type',
        'expiry_date',
        'batch_id',
        'acq_cost',
        'wholesale_cost',
        'retail_cost',
        'unit_cost',
        'supplier_id',
        'created_by',
        'reason',
    ];

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            self::beforeSave($item);

            return true;
        });

        static::created(function (Inventory $item) {
            self::afterSave($item);
        });

        static::updated(function (Inventory $item) {
            self::afterSave($item);
        });
    }

    /**
     * Function to update smth before create item
     */
    public static function beforeCreate($item)
    {
        $current_user = Auth::user();
        $item->created_by = $current_user->id;
    }

    public static function beforeSave($item)
    {
        if ($item->type == self::IN) {
            $item->batch_id = self::getNextBatchId($item->product_id);
        }
    }

    public static function getNextBatchId($productId)
    {
        $last = self::where('type', self::IN)->where('product_id', $productId)->orderBy('batch_id', 'desc')->take(1)->get()->first();

        return $last ? $last->batch_id + 1 : 1;
    }

    public static function afterSave($item)
    {
        $sum = self::where('product_id', $item->product_id)->sum('quantity');
        Product::where('id', $item->product_id)->first()->update(['qoh' => $sum]);
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['productId'] = $this->product_id;
        $json['productCode'] = $this->product->product_code;
        $json['productLabel'] = $this->product->label;
        $json['wholesaleCost'] = $this->product->wholesale_cost;
        $json['retailCost'] = $this->product->retail_cost;
        $json['shelf'] = $this->product->shelf->title;
        $json['quantity'] = $this->quantity;
        $json['type'] = $this->type;
        $json['expiryDate'] = date('Y-m-d', strtotime($this->expiry_date));
        $json['batchId'] = $this->batch_id;
        $json['supplierId'] = $this->supplier_id;
        $json['supplierName'] = $this->supplier->name;
        $json['createdAt'] = date('Y-m-d H:i:s', strtotime($this->created_at));
        $json['reason'] = $this->reason;

        return $json;
    }
}
