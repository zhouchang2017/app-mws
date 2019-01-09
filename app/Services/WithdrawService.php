<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/9
 * Time: 5:51 PM
 */

namespace App\Services;


use App\Models\Withdraw;
use App\Models\WithdrawItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawService
{
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
        // event(new SupplyApprovedEvent($this->supply));
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToUnShip($reason = '等待发货')
    {
        $this->withdraw->setStatus($this->withdraw::UN_SHIP, $reason);
        $this->withdraw->freshNow();
        //event(new SupplyUnShipEvent($this->withdraw));
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
//        event(new SupplyPendingEvent($this->withdraw));
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
        // event(new SupplyShippedEvent($this->withdraw));
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
//        event(new SupplyCompletedEvent($this->withdraw));
    }


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

    public static function resolveWithdrawItem($data)
    {
        if ($id = array_get($data, 'id')) {
            return WithdrawItem::find($id);
        }
        return null;
    }


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

}