<?php

namespace App\Services;

use App\Models\Tenant\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserService extends ApiService
{
    public function __construct(private UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct($userRepository);
    }

    public function getUsers(): Collection
    {
        return User::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->userRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }

    public function createDefaultUser(string $firstName, string $lastName, string $username, string $email, string $password): User
    {
        return $this->userRepository->createDefaultUser(
            $firstName, 
            $lastName,
            $username,
            $email,
            $password
        );
    }
}
