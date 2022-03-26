<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 300)->unique('username');
            $table->string('password', 300);
            $table->string('email', 100)->nullable()->unique('email');
            $table->string('phone', 20)->unique('phone');
            $table->date('dob')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('last_login_time')->default('current_timestamp()');
            $table->string('last_login_ip', 30)->nullable();
            $table->string('last_login_device', 50)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->integer('contact_address_1')->nullable();
            $table->integer('contact_address_2')->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->integer('status')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('support_pin', 11)->nullable()->unique('support_pin');
            $table->string('nid', 20)->nullable();
            $table->string('marital_status', 15)->nullable();
            $table->string('designation')->nullable();
            $table->nullableMorphs('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_admins');
    }
}
