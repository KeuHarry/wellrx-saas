<?php

namespace App\Observers;

use App\Models\Tenant\Prescription;
use Illuminate\Support\Facades\Auth;

class PrescriptionObserver
{
    public function creating(Prescription $prescription): void
    {
        $authUser = Auth::user();
        $prescription->created_by = $authUser ? $authUser->id : 1;
        $prescription->prescription_code = 'RX-'.date('Ymd').'-'.str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Handle the Prescription "created" event.
     */
    public function created(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "updated" event.
     */
    public function updated(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "deleted" event.
     */
    public function deleted(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "restored" event.
     */
    public function restored(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "force deleted" event.
     */
    public function forceDeleted(Prescription $prescription): void
    {
        //
    }
}
