<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletReceiveProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_receive_products', function (Blueprint $table) {
            $table->id();
            $table->integer('issue_id');
            $table->integer('receive_id');
            $table->integer('product_id');
            $table->integer('product_quantity');
            $table->double('purchase_price')->default(0);
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
        Schema::dropIfExists('outlet_receive_products');
    }
}
