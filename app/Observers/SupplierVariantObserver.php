<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/26
 * Time: 3:05 PM
 */

namespace App\Observers;


use App\Models\SupplierVariant;

class SupplierVariantObserver
{
    public function created(SupplierVariant $variant)
    {
        info('关联变体' . $variant->variant_id);
    }
}