<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class SalePayment extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_payments';

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
        'payment_method',
        'reference',
        'tendered',
        'amount',
        'change',
        'cashier_id',
        'cash_register_id',
    ];

    /**
     * Get the sale.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['paymentMethod'] = $this->payment_method;
        $json['reference'] = $this->reference;
        $json['tendered'] = $this->tendered;
        $json['amount'] = $this->amount;
        $json['change'] = $this->change;
        $json['createdAt'] = $this->created_at;

        return $json;
    }
}
