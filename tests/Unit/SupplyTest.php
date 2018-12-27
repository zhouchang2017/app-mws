<?php

namespace Tests\Unit;

use App\Models\Supplier;
use App\Models\Supply;
use App\Services\SupplyService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplyTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * @var Supply
     */
    public $supply;

    public $supplier;

    protected function setUp()
    {
        parent::setUp();
        $this->supply = factory(Supply::class)->make();
//
    }

    

    public function test_create_supply()
    {
        $itemCount = $this->createSupply();
        $this->assertEquals($this->supply->origin,$this->supplier);
        $this->assertEquals($itemCount,$this->supply->items()->count());
    }

    public function test_supply_pending()
    {
        $this->test_create_supply();
        (new SupplyService($this->supply))->statusToPending();
        $this->assertEquals(Supply::PENDING,$this->supply->status);
    }

    public function test_supply_approved()
    {
        $this->test_supply_pending();
        (new SupplyService($this->supply))->statusToApproved();
        $this->assertEquals(Supply::APPROVED,$this->supply->status);
    }


    public function createSupply()
    {
        $this->supplier = $this->randomSupplier();
        // 供应商登录
        auth()->login($this->supplier->users->random());

        $this->supply->origin()->associate($this->supplier);

        $this->supply->save();

        $loop = mt_rand(1, $this->supplier->variants()->count());
        for ($i = 1; $i <= $loop; $i++) {
            $variant = $this->supplier->variants->random();
            // create items
            $this->supply->items()->create([
                'product_id' => $variant->product->id,
                'variant_id' => $variant->id,
                'quantity' => mt_rand(1, 100),
            ]);
        }
        return $loop;
    }

    protected function randomSupplier()
    {
        $supplier = Supplier::all()->random();
        if ($supplier->variants()->count() > 0) {
            return $supplier;
        }
        return $this->randomSupplier();
    }
}
