<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id')->nullable();
            $table->string('product_name', 300)->nullable();
            $table->integer('product_quantity')->nullable();
            $table->integer('product_unit_price')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->string('return_policy', 300)->nullable();
            $table->string('thumbnail_1', 200)->nullable();
            $table->string('product_slug', 150)->nullable();
            $table->string('delivery_partner')->nullable();
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
        Schema::dropIfExists('orders_products');
    }
}
