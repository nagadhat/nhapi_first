<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateReferGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_refer_generations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('username', 100)->nullable();
            $table->integer('L1_id')->nullable();
            $table->string('L1_username', 100)->nullable();
            $table->integer('L2_id')->nullable();
            $table->string('L2_username', 100)->nullable();
            $table->integer('L3_id')->nullable();
            $table->string('L3_username', 100)->nullable();
            $table->integer('L4_id')->nullable();
            $table->string('L4_username', 100)->nullable();
            $table->integer('L5_id')->nullable();
            $table->string('L5_username', 100)->nullable();
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
        Schema::dropIfExists('affiliate_refer_generations');
    }
}
