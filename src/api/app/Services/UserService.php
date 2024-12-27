<?php

namespace App\Services;
use App\Models\Tenant\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(private UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function createUser(
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $password,
        ?int $roleId = null
    ): User
    {
        return $this->userRepository->create($firstName, $lastName, $username, $email, $password, $roleId);
    }

    public function getUser(int $userId): ?User
    {
        return User::find($userId);
    }

    public function getUsers(): Collection
    {
        return User::all();
    }

    public function deleteUser(int $userId): int
    {
        return User::destroy($userId);
    }

    public function updateUser(
        User $user,
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        int $roleId
    ): User
    {
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->username = $username;
        $user->email = $email;
        $user->role_id = $roleId;

        $user->save();

        return $user;
    }

}
