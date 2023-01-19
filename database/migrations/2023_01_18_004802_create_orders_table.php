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
            $table->unsignedSmallInteger('customer_guest')->default(0);
            $table->unsignedSmallInteger('user_id')->default(0);
            $table->unsignedSmallInteger('lines')->default(0);
            $table->unsignedSmallInteger('items_qty')->default(0);
            $table->json('user_data')->default(new Expression('(JSON_OBJECT())'));
            $table->unsignedDecimal('total', 8, 2);
            $table->unsignedDecimal('total_items', 8, 2);
            $table->unsignedDecimal('total_additionals', 8, 2)->default(0);
            $table->unsignedDecimal('total_discount', 8, 2)->default(0);
            $table->unsignedDecimal('total_tax', 8, 2)->default(0);
            $table->unsignedDecimal('total_delivery', 8, 2)->default(0);
            $table->json('delivery_address')->default(new Expression('(JSON_OBJECT())'));
            $table->string('payment_method', 20)->default('');
            $table->integer('coupon_id')->nullable();
            $table->unsignedDecimal('coupon_discount', 8, 2)->default(0);
            $table->unsignedDecimal('total_tip', 4, 2)->default(0);
            $table->string('status', 10)->default('pending');
            $table->enum('store_status', [
                'waiting',
                'preparing',
                'done',
                'delivering',
                'delivered',
                ])->default('waiting');
            $table->enum('payment_status', [0,1,2])->default(0);
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
