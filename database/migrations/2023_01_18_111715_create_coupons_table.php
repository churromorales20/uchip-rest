<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title', 45);
            $table->string('code', 20);
            $table->timestamp('valid_from', $precision = 0);
            $table->timestamp('valid_to', $precision = 0);
            $table->enum('discount_type', [1, 2])->default(1); //1:percentage, 2:fixed_amount
            $table->unsignedDecimal('amount', 6, 2);
            $table->unsignedDecimal('minimum_purchase', 8, 2);
            $table->unsignedMediumInteger('max_coupons')->default(0);
            $table->enum('user_behavior', [0, 1])->default(1); //0:unlimited per customer, 1:once per customer
            $table->enum('available_to', [1, 2])->default(1); //1:all customers, 2:New customers
            $table->unsignedTinyInteger('general_status')->default(0);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->unique('code', 'unique_coupon_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
