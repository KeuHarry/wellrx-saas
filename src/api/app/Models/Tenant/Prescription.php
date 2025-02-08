<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Prescription extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescriptions';

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
        'prescription_code',
        'patient_id',
        'status',
        'total',
        'net_due',
        'discount_percentage',
        'discount_flat',
        'prescription_date',
        'prescription_exp_date',
        'insurance_percentage',
        'insurance_flat',
        'prescriber_id',
        'created_by',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function prescriptionItems(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class, 'prescription_id');
    }

    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(Prescriber::class, 'prescriber_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['prescriptionCode'] = $this->prescription_code;
        $json['patient'] = [
            'id' => $this->id,
            'firstName' => $this->patient->first_name,
            'lastName' => $this->patient->last_name,
        ];
        $json['status'] = $this->status;
        $json['total'] = $this->total;
        $json['netDue'] = $this->net_due;
        $json['discountPercentage'] = $this->discount_percentage;
        $json['discountFlat'] = $this->discount_flat;
        $json['insurancePercentage'] = $this->insurance_percentage;
        $json['insuranceFlat'] = $this->insurance_flat;
        $json['prescriptionDate'] = $this->prescription_date;
        $json['prescriptionExpDate'] = $this->prescription_exp_date;
        $json['prescriber'] = [
            'id' => $this->id,
            'firstName' => $this->prescriber->first_name,
            'lastName' => $this->prescriber->last_name,
        ];
        $json['prescriptionItems'] = $this->prescriptionItems;

        return $json;
    }
}
