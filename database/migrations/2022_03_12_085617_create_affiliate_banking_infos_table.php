<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateBankingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_banking_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('affiliate_id')->nullable();
            $table->string('bank_name', 250)->nullable();
            $table->string('bank_district', 100)->nullable();
            $table->string('bank_branch_name', 100)->nullable();
            $table->string('account_holder_name', 100)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('routing_number', 100)->nullable();
            $table->string('bkash_number', 50)->nullable();
            $table->string('nagad_number', 50)->nullable();
            $table->string('rocket_number', 50)->nullable();
            $table->string('dbbl_agent_number', 50)->nullable();
            $table->integer('status')->nullable();
            $table->string('account_type', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_banking_infos');
    }
}
