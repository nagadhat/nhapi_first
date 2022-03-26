<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_time')->default('current_timestamp()');
            $table->integer('order_id');
            $table->integer('user_id');
            $table->string('user_name', 100);
            $table->string('payer_name', 100)->nullable();
            $table->string('payer_phone', 100)->nullable();
            $table->string('transaction_id')->nullable();
            $table->double('transaction_amound');
            $table->string('payment_id')->nullable();
            $table->string('invoice', 100)->nullable();
            $table->integer('transaction_status')->default(0);
            $table->string('payment_getway', 100)->nullable();
            $table->string('payment_method', 150)->nullable();
            $table->string('bank_name', 200)->nullable();
            $table->string('note_1')->nullable();
            $table->string('note_2')->nullable();
            $table->string('note_3')->nullable();
            $table->string('payment_slip')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
