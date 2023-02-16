<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_additionals', function (Blueprint $table) {
           $table->unsignedBigInteger('product_id');
           $table->unsignedBigInteger('additional_id');
           $table->smallInteger('order')->defaultValue(0);
           $table->unique(['product_id', 'additional_id']);
           $table->foreign('additional_id')->references('id')->on('additionals')->onDelete('cascade');
           $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_additionals');
    }
}
