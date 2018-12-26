<?php

/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 6:02 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Warehouse;

trait UpdateInventoryTrait
{
    /**
     * 计算数量
     * @return mixed
     */
    public function calcTotalPcs()
    {
        return $this->items()->sum('pcs');
    }

    /**
     * 计算价格
     * @return mixed
     */
    public function calcTotalPrice()
    {
        return $this->items()->sum('price');
    }

    /**
     * 计算items 价格|数量
     */
    public function autoCalcItems()
    {
        $this->price = $this->calcTotalPrice();
        $this->pcs = $this->calcTotalPcs();
        return $this;
    }


    /*
     * 标记审核操作
     * */
    public function statusToApproved($reason = '等待发货')
    {
        $this->setStatus(self::UN_SHIP, $reason);
    }


    /*
     * 进/出货单取消
     * */
    public function statusToCancel($reason = '取消')
    {
        $this->setStatus(self::CANCEL, $reason);
    }

    /*
     * 标记提交审核
     * */
    public function statusToPadding($reason = '待审核')
    {
        $this->setStatus(self::PENDING, $reason);
    }


    /*
     * 状态初始化
     * */
    public function statusToSave($reason = '保存/未提交')
    {
        $this->setStatus(self::UN_COMMIT, $reason);
    }

    /*
     * 标记状态部分发货
     * */
    public function statusToPartShipped($reason = '部分发货')
    {
        $this->setStatus(self::PART_SHIPPED, $reason);
    }

    /*
     * 标记状态已发货
     * */
    public function statusToShipped($reason = '已发货')
    {
        $this->setStatus(self::SHIPPED, $reason);
    }

    /*
     * 标记状态已完成
     * */
    public function statusToCompleted($reason = '已完成')
    {
        $this->setStatus(self::COMPLETED, $reason);
    }

    public function canUpdate()
    {
        return $this->status === self::UN_COMMIT;
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}