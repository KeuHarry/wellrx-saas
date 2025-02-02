<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Order extends BaseModel
{
    use HasFactory, Notifiable;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            self::beforeSave($item);

            return true;
        });
    }

    public static function beforeSave($item)
    {
        $item->created_by = Auth::user()->id;
    }

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
        $json['supplier'] = [
            'id' =>  $this->supplier->id,
            'name' => $this->supplier->name
        ];
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
