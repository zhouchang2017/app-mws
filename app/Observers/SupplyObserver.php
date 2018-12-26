<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/26
 * Time: 4:44 PM
 */

namespace App\Observers;


use App\Models\Supply;
use App\Services\SupplyService;

class SupplyObserver
{
    public function created(Supply $supply)
    {
        (new SupplyService($supply))->statusToSave();
    }
}