<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/7
 * Time: 2:47 PM
 */

namespace App\Observers;

use App\Http\Requests\ErpRequest;
use App\Models\DP\ProductVariant;

class ProductVariantObserver
{
    public function created(ProductVariant $variant)
    {
        $request = app(ErpRequest::class);
        if ($request->isSupplier()) {
            $request->user()->supplier->variants()->attach($variant, ['name' => $variant->getName(), 'hidden' => 0]);
        }
    }
}