<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/9
 * Time: 5:51 PM
 */

namespace App\Services;


use App\Events\WithdrawApprovedEvent;
use App\Events\WithdrawCompletedEvent;
use App\Events\WithdrawPendingEvent;
use App\Events\WithdrawShippedEvent;
use App\Events\WithdrawUnShipEvent;
use App\Models\InventoryActionType;
use App\Models\PreInventoryActionOrder;
use App\Models\Withdraw;
use App\Models\WithdrawItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class WithdrawService
 * @package App\Services
 */
class WithdrawService
{
    /**
     * @var Withdraw
     */
    protected $withdraw;

    /**
     * @param Withdraw $withdraw
     */
    public function setWithdraw(Withdraw $withdraw): void
    {
        $this->withdraw = $withdraw;
    }

    /**
     * WithdrawService constructor.
     * @param $withdraw
     */
    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
    }


    /**
     * 标记审核操作
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToApproved($reason = '审核通过，等待仓库拣货')
    {
        $this->withdraw->setStatus($this->withdraw::APPROVED, $reason);
        $this->withdraw->freshNow();
        event(new WithdrawApprovedEvent($this->withdraw));
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToUnShip($reason = '等待发货')
    {
        $this->withdraw->setStatus($this->withdraw::UN_SHIP, $reason);
        $this->withdraw->freshNow();
        event(new WithdrawUnShipEvent($this->withdraw));
    }


    /**
     * 进/出货单取消
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCancel($reason = '取消')
    {
        $this->withdraw->setStatus($this->withdraw::CANCEL, $reason);
        $this->withdraw->freshNow();
    }


    /**
     * 标记提交审核
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPending($reason = '待审核')
    {
        $this->withdraw->setStatus($this->withdraw::PENDING, $reason);
        $this->withdraw->freshNow();
        event(new WithdrawPendingEvent($this->withdraw));
    }


    /**
     * 状态初始化
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToSave($reason = '保存/未提交')
    {
        $this->withdraw->setStatus($this->withdraw::UN_COMMIT, $reason);
        $this->withdraw->freshNow();

    }


    /**
     * 标记状态部分发货
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPartShipped($reason = '部分发货')
    {
        $this->withdraw->setStatus($this->withdraw::PART_SHIPPED, $reason);
        $this->withdraw->freshNow();
    }


    /**
     * 标记状态已发货
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToShipped($reason = '已发货')
    {
        $this->withdraw->setStatus($this->withdraw::SHIPPED, $reason);
        $this->withdraw->freshNow();
        event(new WithdrawShippedEvent($this->withdraw));
    }


    /**
     * 标记状态已完成
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCompleted($reason = '已完成')
    {
        $this->withdraw->setStatus($this->withdraw::COMPLETED, $reason);
        $this->withdraw->freshNow();
        event(new WithdrawCompletedEvent($this->withdraw));
    }


    /**
     * 更新/创建 退仓计划
     * @param Request $request
     * @param Withdraw|null $withdraw
     * @return mixed
     */
    public static function updateOrCreateWithdraw(Request $request, Withdraw $withdraw = null)
    {
        return DB::transaction(function () use ($withdraw, $request) {
            return tap($withdraw ?? new Withdraw(), function ($instance) use ($request) {
                /** @var Withdraw $instance */
                $instance->fill($request->all());
                $instance->origin()->associate(auth()->user()->supplier);
                $instance->save();
                $service = new static($instance);

                // 删除不存在的item
                $service->withdraw->items()->pluck('id')->diff(
                    collect($request->get('items'))->pluck('id')->filter()
                )->each(function ($id) use ($service) {
                    $service->withdraw->items()->find($id)->delete();
                });

                collect($request->get('items'))->each(function ($data) use ($service) {
                    $service->updateOrCreateWithdrawItem($data, static::resolveWithdrawItem($data));
                });

                $service->withdraw->refresh();
            });
        });
    }

    /**
     * 解析 退仓明细
     * @param $data
     * @return null
     */
    public static function resolveWithdrawItem($data)
    {
        if ($id = array_get($data, 'id')) {
            return WithdrawItem::find($id);
        }
        return null;
    }


    /**
     * 更新/创建 退仓明细
     * @param array $data
     * @param WithdrawItem|null $withdrawItem
     * @return mixed
     */
    public function updateOrCreateWithdrawItem(array $data, WithdrawItem $withdrawItem = null)
    {
        return DB::transaction(function () use ($data, $withdrawItem) {
            return tap($withdrawItem ?? new WithdrawItem(), function ($instance) use ($data) {
                /** @var WithdrawItem $instance */
                $instance->fill($data);

                $instance->withdraw()->associate($this->withdraw);
                $instance->save();
            });
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
        $createAt = $this->withdraw->latestStatus($this->withdraw::PENDING)->created_at;
        $user = $this->withdraw->latestStatus($this->withdraw::APPROVED)->user;
        $updateAt = $this->withdraw->latestStatus($this->withdraw::APPROVED)->created_at;
        return [
            'description' => $reason ?? '供应商' . $this->withdraw->origin->name . '于' . $createAt->toDateTimeString() . '提交的退仓申请, 由' . $user->name . '在' . $updateAt->toDateTimeString() . '审核通过，系统推入库存调度中心',
        ];
    }

    /*
     * 创建 预出\入库(入库单\出货单)
     * */
    public function createPreAction(InventoryActionType $type = null, $data = [])
    {
        if (is_null($type)) {
            $type = InventoryActionType::firstOrCreate([
                'name' => '供应商供货退仓',
                'action' => 'take',
                'is_accounting' => true,
            ]);
        }

        return InventoryService::createPreAction(array_merge($this->formatCreatePreActionParams(), $data, [
            'type_id' => $type->id,
        ]), $this->withdraw);
    }


    /**
     * @param PreInventoryActionOrder $order
     * @param Request $request
     * @return mixed
     */
    public function shipment(PreInventoryActionOrder $order, Request $request)
    {
        return tap(InventoryService::preActionOrderShipment($order, $request, true), function () {
            if ($this->isShipped()) {
                $this->statusToShipped();
            } else {
                $this->statusToPartShipped();
            }
        });
    }

    private function isShipped()
    {
        return $this->withdraw->preInventoryAction->orders->every->hasTracks();
    }

}