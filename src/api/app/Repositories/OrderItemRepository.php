<?php

namespace App\Repositories;

use App\Models\Tenant\OrderItem;

class OrderItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new OrderItem();
        parent::__construct();
    }
}
