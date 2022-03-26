<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateOtherBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_other_bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->string('payout_type', 100)->nullable();
            $table->string('purpose', 200)->nullable();
            $table->integer('target')->nullable();
            $table->integer('time_limit_days')->nullable();
            $table->integer('status')->nullable();
            $table->integer('reward')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_other_bonuses');
    }
}
