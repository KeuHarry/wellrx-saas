<?php

namespace App\Observers;

use App\Models\Tenant\Sale;
use App\Repositories\CashRegisterRepository;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Auth;

class SaleObserver
{
    protected $saleRepository;

    protected $cashRegisterRepository;

    public function __construct(SaleRepository $saleRepository, CashRegisterRepository $cashRegisterRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->cashRegisterRepository = $cashRegisterRepository;
    }

    public function creating(Sale $sale): void
    {
        $sale->code = 'Sale#'.$this->saleRepository->getNextId();
        $authUser = Auth::user();
        $sale->cash_register_id = $this->cashRegisterRepository->getCurrentCashRegisterId();
        $sale->created_by = $authUser ? $authUser->id : 1;
    }

    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "updated" event.
     */
    public function updated(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "deleted" event.
     */
    public function deleted(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "restored" event.
     */
    public function restored(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "force deleted" event.
     */
    public function forceDeleted(Sale $sale): void
    {
        //
    }
}
