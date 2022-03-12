<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_categories', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('product_id');
            $table->integer('category_id');
            $table->integer('author_id')->nullable();
            $table->integer('last_modified_id')->nullable();
            $table->dateTime('last_modified')->nullable();
            $table->string('last_modified_ip')->nullable();
            $table->string('last_modified_device')->nullable();
            $table->string('category_name')->nullable();
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
        Schema::dropIfExists('products_categories');
    }
}
