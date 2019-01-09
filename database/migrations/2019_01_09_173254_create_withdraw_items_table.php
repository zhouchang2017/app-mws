<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('withdraw_id');
            $table->unsignedInteger('warehouse_id');
            $table->string('warehouse_area')->default('good')->comment('仓库区域 good = 良品 bad = 次品'); // 仓库区域 good = 良品 bad = 次品
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
        Schema::dropIfExists('withdraw_items');
    }
}
