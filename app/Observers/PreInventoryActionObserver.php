<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/27
 * Time: 10:31 AM
 */

namespace App\Observers;


use App\Models\PreInventoryAction;

class PreInventoryActionObserver
{
    public function created(PreInventoryAction $action)
    {
        $action->statusToPending();
    }
}