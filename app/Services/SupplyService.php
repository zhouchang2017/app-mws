<?php


namespace App\Services;


use App\Events\SupplyApprovedEvent;
use App\Events\SupplyCompletedEvent;
use App\Events\SupplyPendingEvent;
use App\Events\SupplyShippedEvent;
use App\Events\SupplyUnShipEvent;
use App\Models\InventoryActionType;
use App\Models\PreInventoryActionOrder;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function updatedNow()
    {
        $this->supply->update([ 'updated_at' => now() ]);
    }


    /**
     * 标记审核操作
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToApproved($reason = '审核通过，等待分配接收仓库')
    {
        $this->supply->setStatus($this->supply::APPROVED, $reason);
        $this->updatedNow();
        event(new SupplyApprovedEvent($this->supply));
    }

    /**
     * @param string $reason
     * @throws InvalidStatus
     */
    public function statusToUnShip($reason = '等待发货')
    {
        $this->supply->setStatus($this->supply::UN_SHIP, $reason);
        $this->updatedNow();
        event(new SupplyUnShipEvent($this->supply));
    }


    /**
     * 进/出货单取消
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCancel($reason = '取消')
    {
        $this->supply->setStatus($this->supply::CANCEL, $reason);
        $this->updatedNow();
    }


    /**
     * 标记提交审核
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPending($reason = '待审核')
    {
        $this->supply->setStatus($this->supply::PENDING, $reason);
        $this->updatedNow();
        event(new SupplyPendingEvent($this->supply));
    }


    /**
     * 状态初始化
     * @param string $reason
     */
    public function statusToSave($reason = '保存/未提交')
    {
        try {
            $this->supply->setStatus($this->supply::UN_COMMIT, $reason);
            $this->updatedNow();
        } catch (InvalidStatus $e) {
        }
    }


    /**
     * 标记状态部分发货
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPartShipped($reason = '部分发货')
    {
        $this->supply->setStatus($this->supply::PART_SHIPPED, $reason);
        $this->updatedNow();
    }


    /**
     * 标记状态已发货
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToShipped($reason = '已发货')
    {
        $this->supply->setStatus($this->supply::SHIPPED, $reason);
        $this->updatedNow();
        event(new SupplyShippedEvent($this->supply));
    }


    /**
     * 标记状态已完成
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCompleted($reason = '已完成')
    {
        $this->supply->setStatus($this->supply::COMPLETED, $reason);
        $this->updatedNow();
        event(new SupplyCompletedEvent($this->supply));
    }

    /**
     * 创建供货计划
     * @param Request $request
     * @return mixed
     */
    public static function createSupply(Request $request)
    {
        return tap(new Supply($request->all()), function ($supply) use ($request) {
            /** @var Supply $supply */
            $supply->origin()->associate(auth()->user()->supplier);
            $supply->save();
            (new static($supply))->updateOrCreateSupplyItems($request->input('items'));
        });
    }

    /**
     * 更新供货单
     * @param Request $request
     * @return mixed
     */
    public function updateSupply(Request $request)
    {
        return tap($this->supply->update($request->only([ 'description', 'has_ship' ])), function () use ($request) {
            // 删除
            $this->supply->items()->pluck('id')->diff(
                collect($request->get('items'))->pluck('id')->filter()
            )->each(function ($id) {
                $this->supply->items()->find($id)->delete();
            });

            // 创建/更新 Items
            $this->updateOrCreateSupplyItems($request->get('items'));
        });
    }

    /**
     * 创建供货计划明细
     * @param array $data
     * @return mixed
     */
    public function updateOrCreateSupplyItems(array $data)
    {
        return collect($data)->map(function ($item) {
            return $this->supply->items()->updateOrCreate([ 'id' => array_get($item, 'id'), ], $item);
        })->tap(function () {
            $this->supply->loadMissing('items');
        });
    }

    /**
     * 创建 预入库单数据
     * @param null $reason
     * @return array
     */
    protected function formatCreatePreActionParams($reason = null)
    {
        /** @var Carbon $createAt */
        $createAt = $this->supply->latestStatus($this->supply::PENDING)->created_at;
        $user = $this->supply->latestStatus($this->supply::APPROVED)->user;
        $updateAt = $this->supply->latestStatus($this->supply::APPROVED)->created_at;
        return [
            'description' => $reason ?? '供应商' . $this->supply->origin->name . '于' . $createAt->toDateTimeString() . '提交的入库计划申请, 由' . $user->name . '在' . $updateAt->toDateTimeString() . '审核通过，系统推入库存调度中心',
        ];
    }

    /*
     * 创建 预出\入库(入库单\出货单)
     * */
    public function createPreAction(InventoryActionType $type = null, $data = [])
    {
        if (is_null($type)) {
            $type = InventoryActionType::firstOrCreate([
                'name'          => '供应商供货入库',
                'action'        => 'put',
                'is_accounting' => true,
            ]);
        }

        return InventoryService::createPreAction(array_merge($this->formatCreatePreActionParams(), $data, [
            'type_id' => $type->id,
        ]), $this->supply);
    }

    /**
     * 获取预出\入库(入库单\出货单)详情
     * @return mixed
     */
    public function getPreActionOrders()
    {
        return $this->supply->preInventoryAction->orders->map->detail;
    }

    /**
     * 发货，目前供应商供货不做拆单处理，仅针对 入库单发货
     * 已操作单为单位发货
     * @param PreInventoryActionOrder $order
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function shipment(PreInventoryActionOrder $order, Request $request)
    {
        InventoryService::preActionOrderShipment($order, $request, true);
        if ($this->isShipped()) {
            $this->statusToShipped();
        } else {
            $this->statusToPartShipped();
        }
    }

    private function isShipped()
    {
        return $this->supply->preInventoryAction->orders->every->hasTracks();
    }


}