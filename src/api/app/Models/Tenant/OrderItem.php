<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class OrderItem extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';

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
        'order_id',
        'product_id',
        'quantity',
        'expiry_date',
        'price',
        'discount',
        'tax',
        'wholesale_cost',
        'retail_cost',
        'markup',
    ];

    /**
     * Get the order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['product'] = [
            'id' =>  $this->product->id,
            'name' => $this->product->label,
            'shelf' => $this->product->shelf->name
        ];
        $json['quantity'] = $this->quantity;
        $json['expiryDate'] = date('Y-m-d', strtotime($this->expiry_date));
        $json['price'] = $this->price;
        $json['discount'] = $this->discount;
        $json['tax'] = $this->tax;
        $json['wholesaleCost'] = $this->wholesale_cost;
        $json['retailCost'] = $this->retail_cost;
        $json['markup'] = $this->markup;

        return $json;
    }
}
