<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 20)->default('');
            $table->integer('rand_code')->default(0);
            $table->string('order_type', 100)->default('regular');
            $table->integer('user_id');
            $table->string('username', 100)->nullable();
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_email_1', 100)->nullable();
            $table->string('customer_email_2', 100)->nullable();
            $table->string('customer_phone_1', 30)->nullable();
            $table->string('customer_phone_2', 30)->nullable();
            $table->integer('shipping_address')->nullable();
            $table->integer('delivery_address')->nullable();
            $table->string('delivery_note', 300)->nullable();
            $table->timestamp('order_time')->default('current_timestamp()');
            $table->integer('order_status')->default(1);
            $table->integer('total_vendors')->default(1);
            $table->integer('total_delivery_charge')->default(60);
            $table->integer('total_products_price')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
