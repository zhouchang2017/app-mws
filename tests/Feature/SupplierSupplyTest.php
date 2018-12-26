<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\Supply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierSupplyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSupplierCreateSupply()
    {
        factory(Supply::class, 1)->make()->each(function ($supply) {
            $supplier = Supplier::all()->random();
            $supply->origin()->make($supplier);
            $supply->save();
        });
    }
}
