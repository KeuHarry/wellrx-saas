<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class SaleItem extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_items';

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
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'tax',
        'total',
        'prescription_item_id',
        'fee',
    ];

    /**
     * Get the sale.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
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
            'id' => $this->product->id,
            'name' => $this->product->label,
            'shelf' => $this->product->shelf->name,
            'qoh' => $this->product->qoh,
        ];
        $json['quantity'] = $this->quantity;
        $json['price'] = $this->price;
        $json['tax'] = $this->tax;
        $json['fee'] = $this->fee;
        $json['total'] = $this->total;

        return $json;
    }
}
