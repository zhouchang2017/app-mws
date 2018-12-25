<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreInventoryActionOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_inventory_action_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pre_order_id');
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
        Schema::dropIfExists('pre_inventory_action_order_items');
    }
}
