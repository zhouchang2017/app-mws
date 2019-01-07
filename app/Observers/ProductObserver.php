<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/7
 * Time: 2:47 PM
 */

namespace App\Observers;

use App\Http\Requests\ErpRequest;
use App\Models\DP\Product;

class ProductObserver
{
    public function created(Product $product)
    {
        // 产品创建，默认保存状态
        $product->statusToSaved();
        $request = app(ErpRequest::class);
        if ($request->isSupplier()) {
            $request->user()->supplier->products()->attach($product);
        }
    }
}