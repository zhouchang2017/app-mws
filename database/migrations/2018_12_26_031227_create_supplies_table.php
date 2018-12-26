<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 供应产品
        Schema::create('supplies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->nullableMorphs('origin');
            $table->integer('total')->nullable()->default(0);
            $table->boolean('has_ship')->default(true)->comment('是否需要物流');
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
        Schema::dropIfExists('supplies');
    }
}
