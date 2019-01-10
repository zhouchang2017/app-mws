<?php

namespace App\Providers;

use App\Erp\Macros\FirstDayOfPreviousQuarter;
use App\Models\SupplierUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Erp\Macros\FirstDayOfQuarter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::macro('firstDayOfQuarter', new FirstDayOfQuarter);
        Carbon::macro('firstDayOfPreviousQuarter', new FirstDayOfPreviousQuarter);

        Blade::if('admin', function () {
            return auth()->user() instanceof User;
        });

        Blade::if('supplier', function () {
            return auth()->user() instanceof SupplierUser;
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
