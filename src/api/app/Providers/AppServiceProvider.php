<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Models\Tenant\Inventory;
use App\Observers\InventoryObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Cashier::useCustomerModel(Tenant::class);
        Inventory::observe(InventoryObserver::class);
    }
}
