<?php

namespace App\Repositories;

use App\Models\Tenant\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Permission();
        parent::__construct();
    }

    public function getAll(): Collection
    {
        return Permission::all();
    }
}
