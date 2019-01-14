<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableMorphs('origin'); // 账单来源
            $table->unsignedInteger('product_id')->comment('商品');
            $table->unsignedInteger('variant_id')->comment('变体');
            $table->integer('quantity')->comment('数量');
            $table->integer('origin_price')->comment('产品价格');
            $table->integer('price')->comment('支付价格'); // 通过调整记录明细
            $table->json('rest')->default(null)->comment('冗余');
            $table->timestamp('confirm_at')->nullable(); // 确认时间
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
        Schema::dropIfExists('bills');
    }
}
