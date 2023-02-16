<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesBucketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images_bucket', function (Blueprint $table) {
            //$table->id();
            $table->string('image_key', 30);
            $table->unsignedBigInteger('product_id');
            $table->text('base_64');
            $table->timestamps();
            $table->unique('image_key');
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
        Schema::dropIfExists('images_bucket');
    }
}
