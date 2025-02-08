<?php

namespace App\Repositories;

use App\Models\Tenant\Shelf;

class ShelfRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Shelf;
        parent::__construct();
    }
}
