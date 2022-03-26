<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('username', 20)->nullable();
            $table->integer('status')->nullable();
            $table->date('joining_date')->default('current_timestamp()');
            $table->string('user_referral_link', 200)->nullable();
            $table->string('referrer_id', 200)->nullable();
            $table->integer('rank')->default(0);
            $table->integer('team_rank')->default(0);
            $table->integer('power_rank')->default(0);
            $table->integer('refer_count')->default(0);
            $table->double('total_earning')->default(0);
            $table->double('cash_balance')->default(0);
            $table->double('withdrawable')->default(0);
            $table->double('earning_limit')->nullable();
            $table->integer('global_status')->default(0);
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
        Schema::dropIfExists('affiliate_users');
    }
}
