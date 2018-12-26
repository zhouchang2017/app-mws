<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/10
 * Time: 下午5:07
 */

namespace App\Traits;


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

    public function statusToPadding()
    {
        $this->setCheckStatus(self::PENDING);
    }

    public function statusToApproved()
    {
        $this->setCheckStatus(self::APPROVED);
        // TODO update supplier_variants hidden field!
        $this->variants->map->supplierVariant->each->updateHiddenField(0);
    }

    public function statusToRejected()
    {
        $this->setCheckStatus(self::REJECTED);
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
        return $this->{$this->getCheckStatusField()} === self::UN_COMMIT;
    }
}