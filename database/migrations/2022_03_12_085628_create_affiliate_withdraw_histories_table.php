<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateWithdrawHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_withdraw_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('affiliate_id')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('date')->nullable();
            $table->integer('banking_id')->nullable();
            $table->string('billing_method', 100)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('charge')->nullable();
            $table->integer('payable')->nullable();
            $table->string('transection_id', 50)->nullable();
            $table->string('script_pic', 200)->nullable();
            $table->string('issue', 100)->nullable();
            $table->text('note')->nullable();
            $table->dateTime('updated_on')->nullable();
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
        Schema::dropIfExists('affiliate_withdraw_histories');
    }
}
