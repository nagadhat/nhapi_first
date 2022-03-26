<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_sku', 100)->nullable()->unique('product_sku');
            $table->string('short_description', 1000)->nullable();
            $table->string('long_description', 5000)->nullable();
            $table->integer('brand')->nullable();
            $table->string('model', 100)->nullable();
            $table->integer('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('freeshipping')->nullable();
            $table->integer('vendor')->nullable();
            $table->dateTime('availability')->nullable();
            $table->string('social_preview_image', 300)->nullable();
            $table->integer('author_id')->nullable();
            $table->dateTime('upload_time')->nullable();
            $table->dateTime('last_modified')->nullable();
            $table->integer('live_status')->nullable();
            $table->integer('voucher_applicable')->nullable();
            $table->integer('heighest_discount')->nullable();
            $table->string('slug', 150)->nullable()->unique('slug');
            $table->string('thumbnail_1', 150)->nullable();
            $table->string('thumbnail_2', 150)->nullable();
            $table->integer('stock_out_alert')->nullable();
            $table->integer('rating')->nullable();
            $table->integer('special_promo')->nullable();
            $table->integer('last_modified_id')->nullable();
            $table->integer('last_modified_ip')->nullable();
            $table->integer('last_modified_device')->nullable();
            $table->string('return_policy', 300)->nullable();
            $table->string('warranty', 300)->nullable();
            $table->string('money_back', 300)->nullable();
            $table->string('video_platform', 100)->nullable();
            $table->string('video_link', 300)->nullable();
            $table->string('qr_code', 100)->nullable();
            $table->string('img-1', 100)->nullable();
            $table->string('img-2', 100)->nullable();
            $table->string('img-3', 100)->nullable();
            $table->string('img-4', 100)->nullable();
            $table->string('img-5', 100)->nullable();
            $table->string('img-6', 100)->nullable();
            $table->string('img-7', 100)->nullable();
            $table->string('img-8', 100)->nullable();
            $table->string('img-9', 100)->nullable();
            $table->integer('img-10')->nullable();
            $table->string('meta_keyword', 300)->nullable();
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('title', 350);
            $table->integer('flat_delivery_crg')->nullable();
            $table->integer('cod_status')->nullable();
            $table->string('discount_type', 50)->nullable();
            $table->integer('discount_amount')->default(0);
            $table->string('product_type', 50)->default('single');
            $table->double('L1_commission')->default(0);
            $table->double('L2_commission')->default(0);
            $table->double('L3_commission')->default(0);
            $table->double('L4_commission')->default(0);
            $table->double('L5_commission')->default(0);
            $table->double('total_commission')->default(0);
            $table->integer('target_audience')->default(0);
            $table->tinyInteger('barcode_type')->nullable();
            $table->unsignedSmallInteger('outlet_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
