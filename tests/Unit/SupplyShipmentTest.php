<?php

namespace Tests\Unit;

use App\Models\ShipmentTrack;
use App\Models\Supply;
use App\Services\SupplyService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplyShipmentTest extends TestCase
{
    public $supply;

    protected function setUp()
    {
        parent::setUp();
        $this->supply = Supply::find(1);
    }

    public function test_supply_shipment()
    {
        $service = new SupplyService($this->supply);
        $shipment = $service->shipment([
            [
                'order_id' => 1,
                'logistic_id' => 1,
                'tracking_number' => '123456798',
            ],
        ]);


    }

}
