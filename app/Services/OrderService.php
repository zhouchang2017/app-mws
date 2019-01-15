<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/2
 * Time: 5:13 PM
 */

namespace App\Services;


use App\Contracts\Orderable;
use App\Events\CancelOrderEvent;
use App\Events\CompletedOrderEvent;
use App\Events\NewOrderEvent;
use App\Events\PaymentOrderEvent;
use App\Events\ShippedOrderEvent;
use App\Exceptions\OrderHasAlreadyExistsException;
use App\Http\Requests\ErpRequest;
use App\Models\InventoryActionType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PreInventoryActionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $order;

    /**
     * @param Order $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }

    /**
     * OrderService constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getPreInventoryActionDescription()
    {
        return $this->order->description;
    }

    public function getActionType(InventoryActionType $type = null)
    {
        return $type ?? InventoryActionType::firstOrCreate([
                'name' => '订单出库',
                'action' => 'take',
                'is_accounting' => true,
            ]);
    }

    /**
     * 创建 预出货单
     * @return mixed
     */
    public function createPreInventoryAction()
    {
        return InventoryService::createPreAction([
            'description' => $this->getPreInventoryActionDescription(),
            'type_id' => $this->getActionType()->id,
        ], $this->order);
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPending($reason = '等待买家付款')
    {
        $this->order->setStatus($this->order::PENDING, $reason);
    }


    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToUnShip($reason = '买家已付款，等待卖家发货')
    {
        $this->order->setStatus($this->order::UN_SHIP, $reason);
        // 订单付款事件
        event(new PaymentOrderEvent($this->order));
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToPartShipped($reason = '部分发货')
    {
        $this->order->setStatus($this->order::PART_SHIPPED, $reason);
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToShipped($reason = '已发货')
    {
        $this->order->setStatus($this->order::SHIPPED, $reason);
        // 订单已发货事件
        event(new ShippedOrderEvent($this->order));
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCompleted($reason = '交易完成')
    {
        $this->order->setStatus($this->order::COMPLETED, $reason);
        // 订单交易完成事件
        event(new CompletedOrderEvent($this->order));
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCancel($reason = '订单已取消')
    {
        $this->order->setStatus($this->order::CANCEL, $reason);
        // 订单取消事件
        event(new CancelOrderEvent($this->order));
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToUnfulfillable($reason = '订单无法进行配送')
    {
        $this->order->setStatus($this->order::UNFULFILLABLE, $reason);
    }


    /**
     * @param Orderable $orderable
     * @param ErpRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public static function createOrder(Orderable $orderable, ErpRequest $request)
    {
        throw_unless(!$orderable->localOrder, OrderHasAlreadyExistsException::class);
        return DB::transaction(function () use ($orderable, $request) {
            return tap(new Order(), function ($order) use ($orderable, $request) {
                /** @var Order $order */
                $order->fill($request->all());
                $order->origin()->associate($orderable);
                $order->market()->associate(optional($orderable->channel)->market);
                $order->save();

                (new OrderService($order))->statusToPending();
//                $order->se
                collect($request->get('items'))->map(function ($data) use ($order) {
                    return tap(new OrderItem())->forceFill($data)->order()->associate($order)->save();
                });
                // 新订单事件
                event(new NewOrderEvent($order));
            });
        });
    }

    public static function changeOrderStatus(Orderable $orderable, ErpRequest $request)
    {
        return DB::transaction(function () use ($orderable, $request) {
            $service = new static($orderable->localOrder);
            switch ($request->get('status')) {
                case Order::PENDING:
                    $service->statusToPending();
                    break;
                case Order::UN_SHIP:
                    $service->statusToUnShip();
                    break;
                case Order::PART_SHIPPED:
                    $service->statusToPartShipped();
                    break;
                case Order::SHIPPED:
                    $service->statusToShipped();
                    break;
                case Order::COMPLETED:
                    $service->statusToCompleted();
                    break;
                case Order::CANCEL:
                    $service->statusToCancel();
                    break;
                case Order::UNFULFILLABLE:
                    $service->statusToUnfulfillable();
            }
            return $orderable;
        });
    }

    /**
     * 同步订单
     * @param Orderable $orderable
     * @return mixed
     */
    public static function syncOrder(Orderable $orderable)
    {
        return tap(Order::updateOrCreate([
            'origin_id' => $orderable->id,
            'origin_type' => get_class($orderable),
        ], [
            'origin_id' => $orderable->id,
            'origin_type' => get_class($orderable),
            'price' => $orderable->total_price,
            'market_id' => $orderable->market_id,
            'created_at' => $orderable->created_at,
            'updated_at' => $orderable->updated_at,
        ]), function ($order) use ($orderable) {

            (new static($order))->setStatusByOrder($orderable);
        });
    }

    public function createOrderItems()
    {
        return $this->order->getExpendItems()->map(function ($item) {
            return $this->order->items()->create($item);
        });
    }

    public function setStatusByOrder(Orderable $orderable)
    {
        switch ($orderable->getStatusAttribute()) {
            case Order::UN_SHIP:
                $this->statusToUnShip();
                break;
            case Order::PENDING:
                $this->statusToPending();
                break;
            case Order::SHIPPED:
                $this->statusToShipped();
                break;
            case Order::CANCEL:
                $this->statusToCancel();
                break;
            case Order::UNFULFILLABLE:
                $this->statusToUnfulfillable();
                break;
            default:
                $this->order->setStatus('N/A', '未知状态');
        }
    }

    public function shipment(PreInventoryActionOrder $preInventoryActionOrder, Request $request)
    {
        InventoryService::preActionOrderShipment($preInventoryActionOrder, $request, true);
        if ($this->isShipped()) {
            $this->statusToShipped();
        } else {
            $this->statusToPartShipped();
        }
    }

    private function isShipped()
    {
        return $this->order->preInventoryAction->orders->every->hasTracks();
    }
}