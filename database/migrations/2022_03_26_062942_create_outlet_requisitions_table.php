<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('outlet_id');
            $table->unsignedSmallInteger('product_id');
            $table->unsignedSmallInteger('product_quantity');
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
        Schema::dropIfExists('outlet_requisitions');
    }
}
