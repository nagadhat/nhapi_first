<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingsLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->bigInteger('total_sales');
            $table->integer('line_01_sales')->nullable();
            $table->integer('line_02_sales')->nullable();
            $table->integer('line_03_sales')->nullable();
            $table->integer('line_04_sales')->nullable();
            $table->integer('others_line')->nullable();
            $table->string('rewards_details')->nullable();
            $table->integer('rewards_money')->nullable();
            $table->string('rewards_prize')->nullable();
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
        Schema::dropIfExists('rankings_levels');
    }
}
