<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreInventoryActionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 操作单(拣货单\入仓单)
        Schema::create('pre_inventory_action_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_id')->comment('仓库');
            $table->unsignedInteger('pre_inventory_action_id')->comment('预出\入库');
            $table->unsignedInteger('type_id')->comment('操作类型 put/take'); // 操作类型
            $table->text('description')->nullable()->comment('说明');
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
        Schema::dropIfExists('pre_inventory_action_orders');
    }
}
