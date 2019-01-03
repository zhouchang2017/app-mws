<?php

namespace App\Http\Middleware;

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
        View::share('domain', 'supplier');
        $this->generateMenus();
        return $next($request);
    }

    protected function generateMenus()
    {
        \Menu::make('Menu', function ($menu) {
            $menu->add('Home',['route'  => 'supplier.home']);
            $menu->add('供货计划','supplies');
            $menu->add('产品管理','products');
        });
    }
}
