<?php

namespace App\Services;

use App\Repositories\CashRegisterRepository;
use Illuminate\Database\Eloquent\Collection;

class CashRegisterService extends ApiService
{
    public function __construct(private CashRegisterRepository $cashRegisterRepository)
    {
        $this->cashRegisterRepository = $cashRegisterRepository;
        parent::__construct($cashRegisterRepository);
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ): Collection {
        return $this->cashRegisterRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }

    public function updateCurrentCashRegisterAmount(float $amount): void
    {
        $cashRegister = $this->cashRegisterRepository->getCurrentCashRegister();
        $cashRegister->increment('amount', $amount);
    }
}
