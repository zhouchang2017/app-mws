<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/15
 * Time: 10:47 AM
 */

namespace App\Observers;

use App\Models\Bill;
use App\Services\BillService;

class BillObserver
{
    /**
     * @param Bill $bill
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function created(Bill $bill)
    {
        (new BillService($bill))->statusToNotActive();
    }
}