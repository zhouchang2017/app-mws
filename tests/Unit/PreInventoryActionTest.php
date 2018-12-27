<?php

namespace Tests\Unit;

use App\Models\PreInventoryAction;
use App\Models\PreInventoryActionOrder;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PreInventoryActionTest extends SupplyTest
{
    /**
     * @var PreInventoryAction
     */
    public $action;

    protected function setUp()
    {
        parent::setUp();
    }


    public function test_create_pre_inventory_action()
    {
        $this->test_supply_approved();
        $this->action = $this->supply->preInventoryAction;
        $this->instance(PreInventoryAction::class, $this->action);
    }

    public function test_pre_action_approved()
    {
        $this->test_create_pre_inventory_action();

        auth()->login(User::all()->random());
        $this->action->statusToApproved();
        $this->assertEquals(PreInventoryAction::APPROVED, $this->action->status);
    }

    public function test_pre_action_create_order()
    {
        $this->test_pre_action_approved();
        InventoryService::createPreActionOrder($this->action,$this->randomAssignWarehouse());
        $this->action->orders->every(function($order){
            $this->instance(PreInventoryActionOrder::class,$order);
        });
    }

    protected function randomAssignWarehouse()
    {
        return $this->action->origin->items->map(function ($item) {
            return [
                'warehouse_id' => mt_rand(1, 3),
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
            ];
        });
    }


}
