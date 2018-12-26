<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreInventoryActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 预出\入库(入库单\出货单)
        Schema::create('pre_inventory_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('origin'); // 来源
            $table->text('description')->nullable();
            $table->unsignedInteger('type_id')->comment('操作类型 put/take'); // 操作类型
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
        Schema::dropIfExists('pre_inventory_actions');
    }
}
