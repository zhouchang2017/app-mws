<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/15
 * Time: 10:16 AM
 */

namespace App\Traits;


use App\Models\Bill;

trait HasBills
{
    public function bills()
    {
        return $this->morphMany(Bill::class, 'origin');
    }
}