<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class AdminAuthGrandMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        View::share('domain', array_first(explode('.', $request->getHost())));
        $this->generateMenus();
        return $next($request);
    }

    protected function generateMenus()
    {
        \Menu::make('Menu', function ($menu) {
            $menu->add('Home', ['route' => 'admin.home']);
            $menu->add('订单', 'orders');
            $menu->add('供货计划', 'supplies');
            $menu->add('退仓服务', 'withdraws');
            $menu->add('入库单\出货单', 'pre-inventory-actions');
            $menu->add('操作单', 'pre-inventory-action-orders');
            $menu->add('仓库', 'warehouses');
            $menu->add('仓库类型', 'warehouse-types');
            $menu->add('库存', 'inventories');
            $product = $menu->add('产品', 'products');
            $product->add('产品属性', 'product-attributes');
//            $menu->add('产品属性','product-attributes');
            $product->add('产品销售属性', 'product-options');
            $product->add('变体', 'product-variants');
            $menu->add('价格调整类型', 'attachment-types');
            $menu->add('渠道', 'markets');
            $menu->add('促销活动', 'promotions');
            $menu->add('促销计划', 'promotion-plans');
            $menu->add('供应商', 'suppliers');
            $menu->add('站内消息', 'notifications');
        });
    }
}
