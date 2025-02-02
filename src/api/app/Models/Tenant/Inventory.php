<?php

namespace App\Models\Tenant;

use App\Enums\InventoryType;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Inventory extends BaseModel
{
    use HasFactory, Notifiable, SoftDeletes;

    const OUT = 0;

    const IN = 1;

    const SELL = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventories';

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
        'sub_total',
        'supplier_id',
        'created_by',
        'reason',
        'order_item_id'
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

    public function getNextBatchId(int $productId): int
    {
        $last = self::where('type', InventoryType::IN)->where('product_id', $productId)->orderBy('batch_id', 'desc')->take(1)->get()->first();

        return $last ? $last->batch_id + 1 : 1;
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['product'] = [
            'id' =>  $this->product->id,
            'label' => $this->product->label,
            'shelf' => $this->product->shelf->name
        ];
        $json['acqCost'] = $this->acq_cost;
        $json['wholesaleCost'] = $this->wholesale_cost;
        $json['retailCost'] = $this->retail_cost;
        $json['unitCost'] = $this->unit_cost;
        $json['quantity'] = $this->quantity;
        $json['qohBefore'] = $this->qoh_before;
        $json['qohAfter'] = $this->qoh_after;
        $json['type'] = $this->type;
        $json['expiryDate'] = date('Y-m-d', strtotime($this->expiry_date));
        $json['batchId'] = $this->batch_id;
        $json['supplier'] = [
            'id' =>  $this->supplier->id,
            'name' => $this->supplier->name
        ];
        $json['createdAt'] = date('Y-m-d H:i:s', strtotime($this->created_at));
        $json['reason'] = $this->reason;

        return $json;
    }
}
