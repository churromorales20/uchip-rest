<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 80);
            $table->smallInteger('category_id');
            $table->string('image', 50)->nullable();
            $table->float('price', 8, 2);
            $table->float('price_discount', 8, 2);
            $table->mediumText('description');
            $table->unsignedTinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
