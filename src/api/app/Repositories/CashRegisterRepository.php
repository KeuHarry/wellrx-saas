<?php

namespace App\Repositories;

use App\Enums\CashRegisterStatus;
use App\Models\Tenant\CashRegister;
use Illuminate\Database\Eloquent\Collection;

class CashRegisterRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new CashRegister;
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return CashRegister::when($search, function ($query, $search) {
            $query->whereHas('opener', function ($query) use ($search) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$search}%"]);
            });
        })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }

    public function getCurrentCashRegisterId(): int
    {
        $cashRegister = CashRegister::where('status', CashRegisterStatus::OPENED)
            ->orderBy('created_at', 'desc')
            ->take(1)->get()->first();

        return $cashRegister->id;
    }

    public function getCurrentCashRegister(): CashRegister
    {
        return CashRegister::where('status', CashRegisterStatus::OPENED)
            ->orderBy('created_at', 'desc')
            ->take(1)->get()->first();
    }
}
