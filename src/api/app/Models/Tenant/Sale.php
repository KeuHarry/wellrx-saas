<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Sale extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales';

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
        'total',
        'taxes',
        'discount',
        'net_due',
        'tendered',
        'status',
        'cashier_status',
        'type',
        'is_one_time_payment',
        'payment_method',
        'payment_motive',
        'payment_date_limit',
        'completed_date',
        'cashier_id',
        'cash_register_id',
        'customer_id',
        'prescription_id',
        'refunded_sale_id',
        'customer_name',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_one_time_payment' => 'boolean',
    ];

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['code'] = $this->code;
        $json['total'] = $this->total;
        $json['taxes'] = $this->taxes;
        $json['discount'] = $this->discount;
        $json['netDue'] = $this->net_due;
        $json['tendered'] = $this->tendered;
        $json['status'] = $this->status;
        $json['cashierStatus'] = $this->cashier_status;
        $json['type'] = $this->type;
        $json['paymentMethod'] = $this->payment_method;
        $json['isOneTimePayment'] = $this->is_one_time_payment;
        $json['customerName'] = $this->customer_name;
        $json['customer'] = $this->customer ? [
            'id' => $this->customer->id,
            'name' => $this->customer->name,
        ] : null;
        $json['createdAt'] = date('Y-m-d H:i:s', strtotime($this->created_at));
        $json['saleItems'] = $this->saleItems;

        return $json;
    }
}
