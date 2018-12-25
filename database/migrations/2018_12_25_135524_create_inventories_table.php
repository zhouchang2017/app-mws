<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger(config('inventory.product_key'))->nullable();
            $table->unsignedInteger(config('inventory.variant_key'));
            $table->unsignedInteger('warehouse_id')->comment('仓库');
            $table->string('warehouse_area')->default('good')->comment('仓库区域 good = 良品 bad = 次品'); // 仓库区域 good = 良品 bad = 次品
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
        Schema::dropIfExists('inventories');
    }
}
