<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OutletRequisitionProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_requisition_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('requisition_id');
            $table->unsignedSmallInteger('product_id');
            $table->unsignedSmallInteger('product_quantity');
            $table->unsignedSmallInteger('issued_quantity');
            $table->unsignedSmallInteger('remaining_quantity');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('outlet_requisition_products');
    }
}
