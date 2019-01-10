<?php

namespace App\Providers;

use App\Erp\Erp;
use Illuminate\Support\ServiceProvider;

class ErpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->resources();
    }

    /**
     * Register the application's Nova resources.
     *
     * @return void
     * @throws \ReflectionException
     */
    protected function resources()
    {
        Erp::resourcesIn(app_path('Resources'));
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
