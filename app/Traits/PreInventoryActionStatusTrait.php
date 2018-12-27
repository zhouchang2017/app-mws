<?php

namespace App\Traits;


use App\Events\PreInventoryActionApprovedEvent;
use App\Events\PreInventoryActionAssignedEvent;
use App\Events\PreInventoryActionPendingEvent;
use App\Events\PreInventoryActionRejectedEvent;
use App\Models\PreInventoryAction;

trait PreInventoryActionStatusTrait
{
    /**
     * 预出\入库(入库单\出货单) 标记审核操作
     * @param string $reason
     */
    public function statusToApproved($reason = '审核通过，等待分配库存')
    {
        $this->setStatus(PreInventoryAction::APPROVED, $reason);
        /** @var PreInventoryAction $this */
        event(new PreInventoryActionApprovedEvent($this));
    }



    /**
     * 预出\入库(入库单\出货单)货单取消
     * @param string $reason
     */
    public function statusToRejected($reason = '拒绝通过')
    {
        $this->setStatus(PreInventoryAction::REJECTED, $reason);
        /** @var PreInventoryAction $this */
        event(new PreInventoryActionRejectedEvent($this));
    }


    /**
     * 预出\入库(入库单\出货单) 标记提交审核,等待审核
     * @param string $reason
     */
    public function statusToPending($reason = '待仓库调度中心审核')
    {
        $this->setStatus(PreInventoryAction::PENDING, $reason);
        /** @var PreInventoryAction $this */
        event(new PreInventoryActionPendingEvent($this));
    }

    /**
     * 预出\入库(入库单\出货单) 库存已分配完成，生成操作单
     * @param string $reason
     */
    public function statusToAssigned($reason = '以分配库存，生成操作单')
    {
        $this->setStatus(PreInventoryAction::ASSIGNED, $reason);
        /** @var PreInventoryAction $this */
        event(new PreInventoryActionAssignedEvent($this));
    }

    // 提交审核通知
    public function PendingNotify()
    {
        // 通知管理

    }

    // 审核通过通知 -> 分配库存
    public function approvedNotify()
    {
        // 通知仓库分配库存
    }

    // 库存已分配通知 -> 创建操作单
    public function assignedNotify()
    {
        //
    }
}