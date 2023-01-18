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
           $table->smallInteger('product_id');
           $table->smallInteger('additional_id');
           $table->smallInteger('order')->defaultValue(0);
           $table->unique(['product_id', 'additional_id']);
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
