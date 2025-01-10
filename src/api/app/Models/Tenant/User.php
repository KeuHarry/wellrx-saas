<?php

namespace App\Models\Tenant;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'pin',
        'is_active',
        'preferred_lang',
        'change_default_pass',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo('App\Models\Tenant\Role', 'role_id');
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        $json['id'] = $this->id;
        $json['firstName'] = $this->first_name;
        $json['lastName'] = $this->last_name;
        $json['email'] = $this->email;
        $json['username'] = $this->username;
        $json['role'] = $this->getRole() ? [
            'id' => $this->role->id,
            'label' => $this->role->label
        ] : null ;
        $json['pin'] = $this->pin;
        $json['isActive'] = $this->is_active;
        $json['preferredLang'] = $this->preferred_lang;
        $json['changePass'] = $this->change_default_pass;
        $json['createdAt'] = $this->created_at->format('Y-m-d');

        return $json;
    }
}
