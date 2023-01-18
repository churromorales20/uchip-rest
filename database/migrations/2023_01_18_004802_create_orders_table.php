<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            //$table->id();
            $table->increments('id');
            $table->json('user_data')->default(new Expression('(JSON_OBJECT())'));
            $table->float('total', 8, 2);
            $table->float('total_discount', 8, 2)->default(0);
            $table->float('total_tax', 8, 2)->default(0);
            $table->float('total_delivery', 8, 2)->default(0);
            $table->json('delivery_address')->default(new Expression('(JSON_OBJECT())'));
            $table->string('payment_method', 20)->default('');
            $table->integer('coupon_id')->nullable();
            $table->float('coupon_discount', 8, 2)->default(0);
            $table->float('total_tip', 4, 2)->default(0);
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
        Schema::dropIfExists('orders');
    }
}
