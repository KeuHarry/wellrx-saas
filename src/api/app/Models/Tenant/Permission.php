<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Permission extends BaseModel
{
    use Notifiable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['code'] = $this->code;
        $json['label'] = $this->label;

        return $json;
    }
}
