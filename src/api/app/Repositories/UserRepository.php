<?php

namespace App\Repositories;

use App\Models\Tenant\User;

class UserRepository
{
    public function create(
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $password,
        ?int $roleId = null
    ): User
    {
        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        $user->role_id = $roleId;
        $user->save();

        return $user;
    }
}