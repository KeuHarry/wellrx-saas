<?php

namespace App\Repositories;

use App\Models\Tenant\Fee;
use Illuminate\Database\Eloquent\Collection;

class FeeRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Fee;
        parent::__construct();
    }

    public function findFeeByTag(int $tag): Collection
    {
        return Fee::where('tag', $tag)->get();
    }
}
