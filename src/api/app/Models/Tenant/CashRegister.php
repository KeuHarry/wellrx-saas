<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class CashRegister extends BaseModel
{
    use HasFactory, Notifiable;

    const OPEN = 1;

    const CLOSED = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cash_registers';

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
        'status',
        'opening_time',
        'closing_time',
        'opened_by',
        'closed_by',
    ];

    /**
     * Get the opener.
     */
    public function opener()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    /**
     * Get the closer.
     */
    public function closer()
    {
        return $this->belongsTo(User::class, 'close_by');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['openedBy'] = $this->opener->first_name.' '.$this->opener->last_name;
        $json['openingTime'] = date('Y-m-d H:i:s', strtotime($this->opening_time));
        $json['closedBy'] = $this->closed_by;
        $json['closingTime'] = $this->closing_time;
        $json['status'] = $this->status;

        return $json;
    }
}
