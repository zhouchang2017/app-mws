<?php

namespace App\Providers;

use App\Models\DP\Product;
use App\Models\PreInventoryAction;
use App\Models\PreInventoryActionOrder;
use App\Models\Supplier;
use App\Models\SupplierUser;
use App\Models\Supply;
use App\Models\Withdraw;
use App\Policies\PreInventoryActionOrderPolicy;
use App\Policies\PreInventoryActionPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\SupplierUserPolicy;
use App\Policies\SupplyPolicy;
use App\Policies\WithdrawPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Supply::class => SupplyPolicy::class,
        PreInventoryAction::class => PreInventoryActionPolicy::class,
        PreInventoryActionOrder::class => PreInventoryActionOrderPolicy::class,
        Withdraw::class => WithdrawPolicy::class,
        Supplier::class => SupplierPolicy::class,
        SupplierUser::class => SupplierUserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
