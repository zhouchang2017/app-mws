<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/2
 * Time: 5:13 PM
 */

namespace App\Services;


use App\Contracts\Orderable;
use App\Models\InventoryActionType;
use App\Models\Order;
use Illuminate\Http\Request;

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
        InventoryService::createPreAction([
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
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToCancel($reason = '订单已取消')
    {
        $this->order->setStatus($this->order::CANCEL, $reason);
    }

    /**
     * @param string $reason
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function statusToUnfulfillable($reason = '订单无法进行配送')
    {
        $this->order->setStatus($this->order::UNFULFILLABLE, $reason);
    }

    public static function syncOrder(Orderable $orderable)
    {
        return Order::updateOrCreate([
            'origin_id' => $orderable->id,
            'origin_type' => get_class($orderable),
        ], [
            'origin_id' => $orderable->id,
            'origin_type' => get_class($orderable),
            'price' => $orderable->total_price,
            'market_id' => $orderable->market_id,
            'created_at' => $orderable->created_at,
            'updated_at' => $orderable->updated_at,
        ]);
    }

}