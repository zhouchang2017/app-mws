<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('variant_id')->comment('变体id');
            $table->unsignedInteger('supplier_id')->comment('供应商id');
            $table->string('name')->nullable()->comment('冗余变体名称');
            $table->boolean('hidden')->default(false)->comment('是否显示');
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
        Schema::dropIfExists('supplier_variants');
    }
}
