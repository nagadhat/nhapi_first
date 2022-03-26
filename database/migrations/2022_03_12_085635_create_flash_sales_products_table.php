<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSalesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_sales_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('status')->default(0);
            $table->string('discount_type', 30)->default('percentage');
            $table->integer('discount_amount')->default(0);
            $table->integer('maximum_sale_quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flash_sales_products');
    }
}
