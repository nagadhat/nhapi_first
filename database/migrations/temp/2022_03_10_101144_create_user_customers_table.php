<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_customers', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique('username');
            $table->string('password');
            $table->string('first_name', 100)->nullable();
            $table->string('gender', 100)->nullable();
            $table->integer('affiliate_id')->nullable();
            $table->integer('referrer_id')->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('home_default_address')->nullable();
            $table->string('default_phone_number', 20)->nullable();
            $table->integer('old_id')->nullable();
            $table->text('profile_picture')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->date('marriage_anniversary')->nullable();
            $table->integer('num_of_children')->nullable();
            $table->string('nid_no', 100)->nullable();
            $table->string('nid_front', 100)->nullable();
            $table->string('nid_back', 100)->nullable();
            $table->string('nominee_name', 100)->nullable();
            $table->string('nominee_phone', 100)->nullable();
            $table->string('nominee_nid', 100)->nullable();
            $table->string('nominee_relation', 200)->nullable();
            $table->double('cash_balance')->nullable();
            $table->integer('status')->default(0);
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('user_customers');
    }
}
