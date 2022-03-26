<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('affiliate_id')->nullable();
            $table->integer('banking_id')->nullable();
            $table->integer('otp')->nullable();
            $table->string('billing_method', 100)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('charge')->nullable();
            $table->integer('payable')->nullable();
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
        Schema::dropIfExists('temp_withdraw_requests');
    }
}
