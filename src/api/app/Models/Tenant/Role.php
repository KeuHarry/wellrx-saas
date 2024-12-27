<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable;

    public $validation_rules = [
        'label' => 'required|max:225',
        'description' => 'required|max:225'
    ];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'description'
    ];

    /**
     * Get the role permissions.
     */
    public function role_perms(): HasMany
    {
        return $this->hasMany('App\Models\Tenant\RolePermission', 'role_id');
    }


    /**
     * Get the role users.
     */
    public function users(): HasMany
    {
        return $this->hasMany('App\Models\Tenant\User', 'role_id');
    }

    /**
     * Get the order items data.
     */
    /*public function role_users_get_data(): array
    {
        $user_roles = [];
        foreach ($this->role_users as $item) {
            $user_roles[] = $item->getItemData();
        }
        return $user_roles;
    }*/

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['label'] = $this->label;
        $json['description'] = $this->description;

        return $json;
    }
}
