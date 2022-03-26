<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('affiliate_user_id')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->integer('status')->default(1);
            $table->string('payout_type', 100)->nullable();
            $table->string('purpose', 200)->nullable();
            $table->double('earning')->nullable();
            $table->double('balance')->nullable();
            $table->double('withdrawable')->nullable();
            $table->integer('bonus_from')->nullable();
            $table->unsignedInteger('order_product_id')->nullable();
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
        Schema::dropIfExists('affiliate_payouts');
    }
}
