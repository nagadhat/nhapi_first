<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_rewards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->nullableMorphs('rewards');
            $table->string('rewards_value')->nullable();
            $table->boolean('rewards_status')->default(0);
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('claim_rewards');
    }
}
