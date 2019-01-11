<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapSupplierWebRoutes();

        $this->mapAdminWebRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapAdminWebRoutes()
    {
        Route::group(config('erp.admin.router'), function () {
            require base_path('routes/admin.php');
        });
        Route::group(array_merge(array_except(config('erp.admin.router'), ['middleware']),
            ['middleware' => 'web','namespace'=>'App\Http\Controllers\Admin']),
            function () {
                $this->auth();
                $this->get('/wechat/bind/{user}', 'Auth\WechatController@bind')->name('wechat.bind');
            });
    }

    protected function mapSupplierWebRoutes()
    {
        Route::group(config('erp.supplier.router'), function () {
            require base_path('routes/supplier.php');
        });
        Route::group(array_merge(array_except(config('erp.supplier.router'),
            ['middleware']), ['middleware' => 'web','namespace'=>'App\Http\Controllers\Supplier']),
            function () {
                $this->auth();
                $this->get('/wechat/bind/{user}', 'Auth\WechatController@bind')->name('wechat.bind');
            });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }


}
