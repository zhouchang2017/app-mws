<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryActionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_action_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('action')->comment('操作类型 put/take');
            $table->boolean('is_accounting')->default(1)->comment('是否核算');
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
        Schema::dropIfExists('inventory_action_types');
    }
}
