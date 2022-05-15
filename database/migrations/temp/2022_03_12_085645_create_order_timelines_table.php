<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_timelines', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('user_id');
            $table->dateTime('buyback_on')->nullable();
            $table->dateTime('refunded_on')->nullable();
            $table->dateTime('placed_on')->nullable();
            $table->dateTime('paid_on')->nullable();
            $table->dateTime('processing_on')->nullable();
            $table->dateTime('shipped_on')->nullable();
            $table->dateTime('delivered_on')->nullable();
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
        Schema::dropIfExists('order_timelines');
    }
}
