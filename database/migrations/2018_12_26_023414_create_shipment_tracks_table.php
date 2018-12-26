<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('trackable');
            $table->unsignedInteger('logistic_id')->comment('物流公司');
            $table->string('tracking_number')->comment('物流单号');
            $table->integer('price')->default(0)->comment('快递费');
            $table->text('description')->nullable()->comment('备注');
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
        Schema::dropIfExists('shipment_tracks');
    }
}
