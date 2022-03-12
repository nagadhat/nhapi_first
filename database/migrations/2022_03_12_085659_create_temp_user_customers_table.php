<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUserCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_user_customers', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('gender', 100)->nullable();
            $table->string('user_email', 100)->nullable();
            $table->string('user_password')->nullable();
            $table->string('user_otp_code', 10)->nullable();
            $table->timestamps()->default('current_timestamp()');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_user_customers');
    }
}
