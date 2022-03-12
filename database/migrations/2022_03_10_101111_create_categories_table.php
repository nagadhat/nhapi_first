<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('title');
            $table->string('description', 5000)->nullable();
            $table->string('slug')->nullable()->unique('slug');
            $table->integer('author_id')->nullable();
            $table->integer('last_modified_id')->nullable();
            $table->string('last_modified_ip')->nullable();
            $table->string('last_modified_device')->nullable();
            $table->string('icon_image')->nullable();
            $table->string('logo', 300)->nullable();
            $table->string('banner_image', 300)->nullable();
            $table->string('meta_title', 300)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('status')->default(0);
            $table->integer('hover_top_view')->default(0);
            $table->integer('on_slider_menu_view')->default(0);
            $table->integer('home_page_top_menu')->default(0);
            $table->integer('oldCategoriesId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
