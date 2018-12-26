<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 4:01 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\InventoryExpend;

trait ExpendableTrait
{
    public function inventoryExpends()
    {
        return $this->morphMany(InventoryExpend::class, 'expendable');
    }

    public function inventoryExpend()
    {
        return $this->morphOne(InventoryExpend::class, 'expendable');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function reExpend()
    {

    }

    public function cancelExpend()
    {

    }
}