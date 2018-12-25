<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger(config('inventory.product_key'))->nullable();
            $table->unsignedInteger(config('inventory.variant_key'));
            $table->integer('quantity')->comment('数量');
            $table->nullableMorphs('inventoriable'); // 来源
            $table->unsignedInteger('warehouse_id')->comment('仓库');
            $table->string('warehouse_area')->default('good')->comment('仓库区域 good = 良品 bad = 次品'); // 仓库区域 good = 良品 bad = 次品
            $table->unsignedInteger('type_id')->comment('操作类型 put/take'); // 操作类型
            $table->string('action_type_name')->comment('操作类型 put/take'); // 操作类型
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
        Schema::dropIfExists('inventory_actions');
    }
}
