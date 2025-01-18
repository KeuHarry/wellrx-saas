<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Patient extends BaseModel
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patients';

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
        'patient_code',
        'first_name',
        'last_name',
        'dob',
        'address',
        'city',
        'email',
        'phone',
        'company',
        'insurer_id',
        'caretaker_id',
        'intermediary_id',
        'allergies',
        'medecine_history',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(
            function ($item) {
                return self::beforeSave($item);
            }
        );

        static::saving(
            function ($item) {
                if (empty($item->patient_code)) {
                    $item->patient_code = 'PAT'.str_pad(strval(self::getNextId()), 4, '0', STR_PAD_LEFT);
                }

                return true;
            }
        );
    }

    /**
     * Function to do smth before save
     */
    public static function beforeSave(Patient $item): bool
    {
        return true;
    }

    public static function getNextId(): mixed
    {
        $lastPatient = Patient::orderBy('id', 'desc')->first();

        return $lastPatient ? $lastPatient->id + 1 : 1;
    }

    /**
     * Get the insurer.
     */
    public function insurer()
    {
        return $this->belongsTo(Insurer::class, 'insurer_id');
    }

    /**
     * Get the caretaker.
     */
    public function caretaker()
    {
        return $this->belongsTo(Patient::class, 'caretaker_id');
    }

    /**
     * Get the intermediary.
     */
    public function intermediary(): BelongsTo
    {
        return $this->belongsTo(Intermediary::class, 'intermediary_id');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['patientCode'] = $this->patient_code;
        $json['firstName'] = $this->first_name;
        $json['lastName'] = $this->last_name;
        $json['dob'] = date('Y-m-d', strtotime($this->dob));
        $json['address'] = $this->address;
        $json['city'] = $this->city;
        $json['email'] = $this->email;
        $json['phone'] = $this->phone;
        $json['company'] = $this->company;
        $json['insurerId'] = $this->insurer_id;
        $json['insurer'] = $this->insurer ? $this->insurer->name : $this->insurer;
        $json['caretakerId'] = $this->caretaker_id;
        $json['caretaker'] = $this->caretaker ? ($this->caretaker->firstName.' '.$this->caretaker->lastName) : $this->caretaker;
        $json['allergies'] = $this->allergies;
        $json['medecineHistory'] = $this->medecine_history;

        return $json;
    }
}
