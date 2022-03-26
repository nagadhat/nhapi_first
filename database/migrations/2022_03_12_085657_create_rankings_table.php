<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('total_sales')->nullable();
            $table->unsignedInteger('total_line')->nullable();
            $table->unsignedInteger('line_01')->nullable();
            $table->unsignedInteger('line_02')->nullable();
            $table->unsignedInteger('line_03')->nullable();
            $table->unsignedInteger('line_04')->nullable();
            $table->unsignedInteger('line_05')->nullable();
            $table->unsignedInteger('others_line')->nullable();
            $table->smallInteger('rank')->default(0);
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
        Schema::dropIfExists('rankings');
    }
}
