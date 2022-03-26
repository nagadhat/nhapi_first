<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUserVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_user_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('password', 250)->nullable();
            $table->string('users_name', 100)->nullable();
            $table->integer('otp')->nullable();
            $table->string('shop_type', 100)->nullable();
            $table->string('shop_name', 300)->nullable();
            $table->string('location', 200)->nullable();
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
        Schema::dropIfExists('temp_user_vendors');
    }
}
