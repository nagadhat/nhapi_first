<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->nullable();
            $table->string('description', 1000)->nullable();
            $table->integer('target')->nullable();
            $table->integer('time_limit')->nullable();
            $table->integer('rank_level')->nullable()->unique('rank_level');
            $table->integer('status')->default(0);
            $table->string('slug', 150)->nullable()->unique('slug');
            $table->integer('reward_money')->nullable();
            $table->string('reward_details', 3000)->nullable();
            $table->string('logo', 100)->nullable();
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
        Schema::dropIfExists('affiliate_ranks');
    }
}
