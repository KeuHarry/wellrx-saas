<?php

namespace App\Repositories;

use App\Models\Tenant\Fee;

class FeeRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Fee();
        parent::__construct();
    }
}
