<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/27
 * Time: 9:55 AM
 */

namespace App\Services;


use App\Models\Inventory;
use App\Models\PreInventoryAction;
use App\Models\PreInventoryActionOrder;
use App\Models\PreInventoryActionOrderItem;
use App\Models\PreInventoryActionOrderItemState;
use Illuminate\Http\Request;

class  InventoryService
{
    /*
     * 创建 预出\入库(入库单\出货单)
     * */
    public static function createPreAction($data, $origin = null)
    {
        return tap(new PreInventoryAction($data), function ($preAction) use ($origin) {
            /** @var PreInventoryAction $preAction */
            $preAction->origin()->associate($origin)->save();
        });
    }

    /**
     * 创建 操作单(拣货单\入仓单)
     * @param PreInventoryAction $action
     * @param Request $request
     * @return mixed
     */
    public static function createPreActionOrder(PreInventoryAction $action, Request $request)
    {
        return collect($request->all())->map(function ($items) use ($action) {
            /** @var PreInventoryActionOrder $order */
            $order = $action->orders()->create([
                'warehouse_id' => $items['warehouse_id'],
                'description' => $items['description'],
                'type_id' => $action->type->id,
            ]);
            collect($items['items'])->each(function ($item) use ($order) {
                (new static)->createPreActionOrderItem($order, $item);
            });
            return $order;
        })->tap(function () use ($action) {
            // 操作单(拣货单\入仓单) 创建完成
            $action->statusToAssigned();
        });
    }


    /**
     * 创建 操作单(拣货单\入仓单) 明细
     * @param PreInventoryActionOrder $order
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createPreActionOrderItem(PreInventoryActionOrder $order, $data)
    {
        return $order->items()->create($data);
    }

    /**
     * 操作单发货
     * @param PreInventoryActionOrder $order
     * @param Request $request
     * @param bool $fill
     * @return mixed
     */
    public static function preActionOrderShipment(PreInventoryActionOrder $order, Request $request, $fill = false)
    {
        return $order->toShipment($request->all(), $fill);
    }

    public static function preActionOrderCheck(PreInventoryActionOrder $order, Request $request)
    {

    }

    /**
     * @param PreInventoryActionOrderItem $item
     * @param Request $request
     * @return \App\Models\PreInventoryActionOrderItemState
     * @throws \Exception
     */
    public static function preActionOrderItemCheck(PreInventoryActionOrderItem $item, Request $request)
    {
        return tap($item->addCheck($request->get('quantity'), $request->get('warehouse_area')),
            function ($state) use ($request) {
                if ($request->has('attachments') && count($request->get('attachments')) > 0) {
                    collect($request->get('attachments'))->each(function ($data) use ($state) {
                        /** @var PreInventoryActionOrderItemState $state */
                        $state->addAttachment($data);
                    });
                    $state->loadMissing(['attachments']);
                }
            });
    }
}