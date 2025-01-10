<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Order extends BaseModel
{
    use HasFactory, Notifiable;

    const IN_PROGRESS = 0;

    const COMPLETED = 1;

    const RETURNED = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
        'invoice_code',
        'supplier_id',
        'invoice_date',
        'status',
        'sub_total',
        'tax_total',
        'total',
        'charges',
        'comment',
        'created_by',
    ];

    /**
     * Get the supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /* Get all order items related to order */
    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['invoiceCode'] = $this->invoice_code;
        $json['supplierId'] = $this->supplier_id;
        $json['supplier'] = $this->supplier->name;
        $json['invoiceDate'] = date('Y-m-d', strtotime($this->invoice_date));
        $json['status'] = $this->status;
        $json['subTotal'] = $this->sub_total;
        $json['taxTotal'] = $this->tax_total;
        $json['total'] = $this->total;
        $json['charges'] = $this->charges;
        $json['comment'] = $this->comment;
        $json['orderItems'] = $this->order_items;

        return $json;
    }
}
