<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 供应明细
        Schema::create('supply_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supply_id');
            $table->unsignedInteger(config('inventory.product_key'))->nullable();
            $table->unsignedInteger(config('inventory.variant_key'));
            $table->integer('quantity')->comment('数量');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_items');
    }
}
