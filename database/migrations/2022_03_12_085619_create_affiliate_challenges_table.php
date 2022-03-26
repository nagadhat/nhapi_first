<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->integer('status')->nullable();
            $table->string('challenge_type', 150)->nullable();
            $table->integer('nca_target')->nullable();
            $table->integer('sale_target')->nullable();
            $table->integer('time_limit')->nullable();
            $table->date('date_limit')->nullable();
            $table->integer('cash_reward')->nullable();
            $table->string('reward_details', 200)->nullable();
            $table->string('logo', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_challenges');
    }
}
