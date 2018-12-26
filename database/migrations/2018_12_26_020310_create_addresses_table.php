<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('collection_name')->nullable()->comment('自定义地址归类');
            $table->string('name')->comment('联系人');
            $table->string('tel')->nullable()->comment('联系电话');
            $table->string('phone')->nullable()->comment('手机');
            $table->string('fax')->nullable()->comment('传真号码');
            $table->string('zip')->nullable()->comment('邮编');
            $table->string('country')->nullable()->comment('国家');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('district')->nullable()->comment('区/城镇');
            $table->string('address')->nullable()->comment('详细地址');
            $table->morphs('addressable');
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
        Schema::dropIfExists('addresses');
    }
}
