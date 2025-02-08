<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
use App\Models\Tenant\Role;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService extends ApiService
{
    public function __construct(
        private RoleRepository $roleRepository,
        private PermissionRepository $permissionRepository
    ) {
        parent::__construct($roleRepository);
    }

    public function search(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        int $offset = 0,
        int $limit = 10
    ): Collection {

        $roles = Role::where('label', 'LIKE', '%'.$search.'%')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        if (! assert($roles instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $roles;
    }

    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    public function deletePermissions(int $roleId): void
    {
        $this->roleRepository->deletePermissions($roleId);
    }

    /**
     * @param  int[]  $permissionIds
     */
    public function assignPermissions(int $roleId, array $permissionIds = []): bool
    {
        $this->roleRepository->deletePermissions($roleId);

        return $this->roleRepository->assignPermissions($roleId, $permissionIds);
    }

    public function getPermissionsCount(): int
    {
        return $this->roleRepository->permissionsCount();
    }
}
