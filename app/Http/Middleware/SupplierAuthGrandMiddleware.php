<?php

namespace App\Http\Middleware;

use App\Erp\Charts\Metrics\ProductVariants;
use App\Erp\Erp;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class SupplierAuthGrandMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        View::share('domain', array_first(explode('.',$request->getHost())));
        $this->generateMenus();
        $this->cards();
        return $next($request);
    }

    protected function generateMenus()
    {
        \Menu::make('Menu', function ($menu) {
            $menu->add('Home',['route'  => 'supplier.home']);
            $menu->add('个人中心', ['route' => 'supplier.users.profile']);
            $menu->add('供应商中心', ['route' => 'supplier.suppliers.profile']);
            $menu->add('供货计划','supplies');
            $menu->add('促销活动','promotion-plans');
            $menu->add('产品管理','products');
            $menu->add('退仓申请','withdraws');
            $menu->add('站内消息','notifications');
        });
    }

    protected function cards()
    {
        Erp::cards(
            [
                new ProductVariants(),
            ]
        );
    }
}
