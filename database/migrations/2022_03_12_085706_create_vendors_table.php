<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('email', 200)->nullable()->unique('email');
            $table->string('phone', 20)->nullable()->unique('phone');
            $table->string('username', 100)->nullable()->unique('username');
            $table->string('password', 250)->nullable();
            $table->string('contact_number_1', 20)->nullable();
            $table->string('contact_number_2', 20)->nullable();
            $table->string('contact_email_1', 100)->nullable();
            $table->string('contact_email_2', 100)->nullable();
            $table->string('vendor_unique_code', 100)->nullable()->unique('vendor_unique_code');
            $table->string('vendor_support_pin', 20)->nullable()->unique('vendor_support_pin');
            $table->string('shop_name', 300)->nullable();
            $table->string('responsible_person_full_name', 100)->nullable();
            $table->integer('status')->nullable();
            $table->integer('holiday_mode')->nullable();
            $table->dateTime('holiday_start_date')->nullable();
            $table->dateTime('holiday_end_date')->nullable();
            $table->string('owner_name', 100)->nullable();
            $table->string('responsible_person_phone', 100)->nullable();
            $table->string('responsible_person_email', 100)->nullable();
            $table->integer('responsible_person_address')->nullable();
            $table->string('responsible_person_nid', 50)->nullable();
            $table->string('responsible_person_nid_front', 200)->nullable();
            $table->string('responsible_person_nid_back', 200)->nullable();
            $table->string('nominee_name', 100)->nullable();
            $table->integer('nominee_address')->nullable();
            $table->string('nominee_contact_number', 20)->nullable();
            $table->string('nominee_email', 100)->nullable();
            $table->string('nominee_nid', 100)->nullable();
            $table->string('shop_type', 100)->nullable();
            $table->string('business_product_type', 200)->nullable();
            $table->string('location', 200)->nullable();
            $table->string('business_reg_number', 100)->nullable();
            $table->string('business_tin', 100)->nullable();
            $table->string('business_document', 100)->nullable();
            $table->string('business_visiting_card', 100)->nullable();
            $table->string('bank_check', 100)->nullable();
            $table->string('trade_license', 100)->nullable();
            $table->string('trade_license_file', 200)->nullable();
            $table->integer('warehouse_address_1')->nullable();
            $table->integer('warehouse_address_2')->nullable();
            $table->integer('warehouse_address_3')->nullable();
            $table->integer('warehouse_address_4')->nullable();
            $table->integer('warehouse_address_5')->nullable();
            $table->integer('head_office_address')->nullable();
            $table->integer('return_address_1')->nullable();
            $table->integer('return_address_2')->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('banner', 100)->nullable();
            $table->integer('rating')->nullable();
            $table->string('aggrement_paper', 100)->nullable();
            $table->string('delivery_option', 100)->nullable();
            $table->string('slug', 100)->nullable();
            $table->string('payment_bkash', 20)->nullable();
            $table->string('payment_rocket', 20)->nullable();
            $table->string('payment_nagad', 20)->nullable();
            $table->string('payment_upay', 20)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_name', 50)->nullable();
            $table->string('bank_account_branch', 50)->nullable();
            $table->string('bank_account_routing', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
