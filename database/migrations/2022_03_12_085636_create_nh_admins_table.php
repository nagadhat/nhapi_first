<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nh_admins', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('support_pin')->nullable();
            $table->string('nid')->nullable()->unique('nh_admins_nid_unique');
            $table->string('marital_status')->nullable();
            $table->string('designation')->nullable();
            $table->string('email_2')->nullable();
            $table->string('phone_2')->nullable()->unique('nh_admins_phone_2_unique');
            $table->integer('address')->nullable();
            $table->integer('address_2')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('dob')->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->timestamp('last_login_time')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('last_login_device')->nullable();
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
        Schema::dropIfExists('nh_admins');
    }
}
