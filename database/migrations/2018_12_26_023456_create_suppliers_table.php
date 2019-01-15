<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 供应商
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable()->comment('供应商代码')->unique();
            $table->unsignedTinyInteger('level')->default(0)->comment('供应商等级');
            $table->text('description')->nullable()->comment('供应商描述说明');
            $table->unsignedInteger('supplier_user_id')->nullable()->comment('供应商管理员');
            $table->unsignedInteger('user_id')->nullable()->comment('跟进人');
            $table->bigInteger('balance')->default(0)->comment('供应商余额');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
