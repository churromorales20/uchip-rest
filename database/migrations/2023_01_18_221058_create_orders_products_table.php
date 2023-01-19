<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
class CreateOrdersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_products', function (Blueprint $table) {
            $table->integer('order_id');
            $table->integer('product_id');
            $table->unsignedSmallInteger('qty');
            $table->unsignedDecimal('unit_price', 8, 2);
            $table->unsignedDecimal('total_sell', 8, 2);
            $table->unsignedDecimal('total_item', 8, 2);
            $table->unsignedDecimal('total_discount', 8, 2);
            $table->unsignedDecimal('total_additionals', 8, 2);
            $table->json('additionals')->default(new Expression('(JSON_ARRAY())'));
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_products');
    }
}
