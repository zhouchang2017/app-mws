<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 3:58 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\InventoryIncome;

trait IncomeableTrait
{
    public function inventoryIncomes()
    {
        return $this->morphMany(InventoryIncome::class, 'incomeable');
    }

    public function inventoryIncome()
    {
        return $this->morphOne(InventoryIncome::class, 'incomeable');
    }
}