<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/15
 * Time: 10:48 AM
 */

namespace App\Services;


use App\Models\Bill;

class BillService
{
    protected $bill;

    /**
     * @param Bill $bill
     */
    public function setBill(Bill $bill): void
    {
        $this->bill = $bill;
    }

    /**
     * BillService constructor.
     * @param $bill
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    /**
     * 账单生成
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToNotActive($reason = '账单初始化，尚未生效')
    {
        $this->bill->setStatus($this->bill::NOT_ACTIVE, $reason);
        $this->bill->freshNow();
    }

    /**
     * 账单生效
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToActive($reason = '账单生效')
    {
        $this->bill->setStatus($this->bill::ACTIVE, $reason);
        $this->bill->freshNow();
    }

    /**
     * 供应商核对账单
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToConfirmed($reason = '供应商以确认账单')
    {
        $this->bill->setStatus($this->bill::CONFIRMED, $reason);
        $this->bill->supplier->changeBalance($this->bill);
        $this->bill->freshNow();
    }

    /**
     * 账单失效
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCancel($reason = '账单已失效,订单关闭')
    {
        $this->bill->setStatus($this->bill::CANCEL, $reason);
        $this->bill->freshNow();
    }
}