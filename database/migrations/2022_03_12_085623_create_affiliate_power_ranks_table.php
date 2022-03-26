<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatePowerRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_power_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('logo', 200)->nullable();
            $table->integer('status')->default(1);
            $table->integer('target_sales_number')->nullable();
            $table->integer('time_limit_days')->nullable();
            $table->double('L1_commission')->nullable();
            $table->double('L2_commission')->nullable();
            $table->double('L3_commission')->nullable();
            $table->double('L4_commission')->nullable();
            $table->double('L5_commission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_power_ranks');
    }
}
