<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class PrescriptionItem extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescription_items';

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
        'prescription_id',
        'product_id',
        'quantity',
        'course_days',
        'refill_allowed_times',
        'fee',
        'tax',
        'cost',
        'total',
    ];

    /**
     * Get the prescription.
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function labelCodes()
    {
        return $this->belongsToMany(LabelCode::class, 'prescription_item_label_codes', 'prescription_item_id', 'label_code_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['product'] = [
            'id' => $this->product->id,
            'name' => $this->product->label,
        ];
        $json['quantity'] = $this->quantity;
        $json['courseDays'] = $this->course_days;
        $json['refillAllowedTimes'] = $this->refill_allowed_times;
        $json['fee'] = $this->fee;
        $json['tax'] = $this->tax;
        $json['cost'] = $this->cost;
        $json['total'] = $this->total;
        $json['labelCodes'] = $this->labelCodes;

        return $json;
    }
}
