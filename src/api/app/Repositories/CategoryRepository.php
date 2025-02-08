<?php

namespace App\Repositories;

use App\Models\Tenant\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Category;
        parent::__construct();
    }
}
