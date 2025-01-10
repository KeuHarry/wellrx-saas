<?php

namespace App\Repositories;

use App\Models\Tenant\Role;
use App\Models\Tenant\RolePermission;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Role();
        parent::__construct();
    }

    public function deletePermissions(int $id)
    {
        RolePermission::where('role_id', $id)->delete();
    }

    /**
     * @param int[] $permissionIds
     */
    public function assignPermissions(int $roleId, array $permissionIds): bool
    {
        // Prepare the data for bulk insert
        $insertData = array_map(function ($permissionId) use ($roleId) {
            return [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
            ];
        }, $permissionIds);

        return DB::table('role_permissions')->insert($insertData);
    }
}
