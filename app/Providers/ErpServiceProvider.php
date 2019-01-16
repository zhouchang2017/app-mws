<?php

namespace App\Providers;

use App\Erp\Charts\Metrics\ProductVariants;
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
        if ( !in_array(subDomain(), [ 'admin', 'supplier' ])) {
            // abort(404);
        }

        adminComing() ? $this->loadAdminConfig() : $this->loadSupplierConfig();
    }

    public function loadAdminConfig()
    {
        // app name
        config([ 'app.name' => 'ERP系统管理后台' ]);
    }
    public function loadSupplierConfig()
    {
        // app name
        config([ 'auth.name' => '供应商管理后台' ]);
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
