<?php


namespace App\Services;


use App\Events\SupplyPendingEvent;
use App\Events\SupplyUnShipEvent;
use App\Models\Supply;
use Spatie\ModelStatus\Exceptions\InvalidStatus;

class SupplyService
{
    protected $supply;

    /**
     * @param Supply $supply
     */
    public function setSupply(Supply $supply): void
    {
        $this->supply = $supply;
    }

    /**
     * SupplyService constructor.
     * @param $supply
     */
    public function __construct(Supply $supply)
    {
        $this->supply = $supply;
    }

    /*
     * 标记审核操作
     * */
    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToApproved($reason = '等待发货')
    {
        $this->supply->setStatus($this->supply::UN_SHIP, $reason);
        event(new SupplyUnShipEvent($this->supply));
    }


    /*
     * 进/出货单取消
     * */
    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCancel($reason = '取消')
    {
        $this->supply->setStatus($this->supply::CANCEL, $reason);
    }

    /*
     * 标记提交审核
     * */
    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPending($reason = '待审核')
    {
        $this->supply->setStatus($this->supply::PENDING, $reason);
        event(new SupplyPendingEvent($this->supply));
    }


    /*
     * 状态初始化
     * */
    /**
     * @param string $reason
     */
    public function statusToSave($reason = '保存/未提交')
    {
        try {
            $this->supply->setStatus($this->supply::UN_COMMIT, $reason);
        } catch (InvalidStatus $e) {
        }
    }

    /*
     * 标记状态部分发货
     * */
    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPartShipped($reason = '部分发货')
    {
        $this->supply->setStatus($this->supply::PART_SHIPPED, $reason);
    }

    /*
     * 标记状态已发货
     * */
    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToShipped($reason = '已发货')
    {
        $this->supply->setStatus($this->supply::SHIPPED, $reason);
    }

    /*
     * 标记状态已完成
     * */
    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCompleted($reason = '已完成')
    {
        $this->supply->setStatus($this->supply::COMPLETED, $reason);
    }


}