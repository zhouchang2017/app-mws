<?php


namespace App\Services;


use App\Events\SupplyApprovedEvent;
use App\Events\SupplyPendingEvent;
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


    /**
     * 标记审核操作
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToApproved($reason = '审核通过，等待分配接收仓库')
    {
        $this->supply->setStatus($this->supply::APPROVED, $reason);
        event(new SupplyApprovedEvent($this->supply));
    }

    /**
     * @param string $reason
     * @throws InvalidStatus
     */
    public function statusToUnShip($reason = '等待发货')
    {
        $this->supply->setStatus($this->supply::UN_SHIP, $reason);
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
    }


    /**
     * 标记提交审核
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPending($reason = '待审核')
    {
        $this->supply->setStatus($this->supply::PENDING, $reason);
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
    }


    /**
     * 标记状态已发货
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToShipped($reason = '已发货')
    {
        $this->supply->setStatus($this->supply::SHIPPED, $reason);
    }


    /**
     * 标记状态已完成
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCompleted($reason = '已完成')
    {
        $this->supply->setStatus($this->supply::COMPLETED, $reason);
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
            (new static($supply))->createSupplyItems($request->input('items'));
        });
    }

    /**
     * 创建供货计划明细
     * @param array $data
     * @return mixed
     */
    public function createSupplyItems(array $data)
    {
        $this->supply->items()->createMany($data);
        $this->supply->loadMissing('items');
        return $this->supply->items;
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
                'name' => '供应商供货入库',
                'action' => 'put',
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
     * @param $data
     * @return \Illuminate\Support\Collection
     */
    public function shipment($data)
    {
        //orderModel->shipment
        //order_id
        //logistic_id
        //tracking_number
        return collect($data)->map(function ($logistic) {
            /** @var PreInventoryActionOrder $preOrder */
            $preOrder = PreInventoryActionOrder::find($logistic['order_id']);
            return $preOrder->toShipment(array_except($logistic, 'order_id'), true);
        })->tap(function () {
            // 物流检测 preInventoryAction ->orders 全部由物流 -> 发货完成  部分有物流 -> 部分发货
        });

    }


}