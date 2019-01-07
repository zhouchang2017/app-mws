<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/10
 * Time: 下午5:07
 */

namespace App\Traits;


use App\Events\ProductApprovedEvent;
use App\Events\ProductPendingEvent;
use App\Events\ProductRejectedEvent;
use App\Http\Requests\ErpRequest;
use App\Models\DP\Product;

trait ProductCheckStatus
{
    public static $checkState = 'check_state';

    public function checkStatus()
    {
        return 'check_state';
    }

    public function getCheckStatusField()
    {
        return $this->checkStatus() ?? self::$checkState;
    }

    public function statusToSaved()
    {
        $this->setCheckStatus(self::UN_COMMIT);
    }

    public function statusToPending()
    {
        $this->setCheckStatus(self::PENDING);
        /** @var Product $this */
        event(new ProductPendingEvent($this));
    }

    public function statusToApproved()
    {
        $this->setCheckStatus(self::APPROVED);
        /** @var Product $this */
        event(new ProductApprovedEvent($this));
    }

    public function statusToRejected()
    {
        $this->setCheckStatus(self::REJECTED);
        /** @var Product $this */
        event(new ProductRejectedEvent($this));
        $this->statusToSaved();
    }

    public function statusToPostponed()
    {
        $this->setCheckStatus(self::POSTPONED);
    }

    protected function setCheckStatus(string $status)
    {
        $this->{$this->getCheckStatusField()} = $status;
        $this->save();
        $this->refresh();
    }

    public function getCheckStatus()
    {
        return $this->{$this->getCheckStatusField()};
    }

    public function canUpdate()
    {
        $request = app(ErpRequest::class);
        if ($request->isAdmin()) {
            return true;
        }
        return $this->{$this->getCheckStatusField()} === self::UN_COMMIT;
    }

}