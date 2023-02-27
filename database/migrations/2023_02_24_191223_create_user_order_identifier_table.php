<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrderIdentifierTable extends Migration
{
    public function up()
    {
        Schema::create('user_order_identifier', function (Blueprint $table) {
            $table->string('order_identifier', 60);
            $table->unsignedBigInteger('order_id');
            
            // Define primary key
            $table->primary(['order_identifier', 'order_id']);
            
            // Define index on order_identifier field
            $table->index('order_identifier');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_order_identifier');
    }
}
