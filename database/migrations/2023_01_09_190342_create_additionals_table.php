<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->enum('single', [0, 1])->default(1);
            $table->enum('required', [0, 1])->default(0);
            $table->smallInteger('max_items')->default(1);
            $table->smallInteger('min_items')->default(0);
            //$table->json('items_data')->default(new Expression('(JSON_ARRAY())'));
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
        Schema::dropIfExists('additionals');
    }
}
