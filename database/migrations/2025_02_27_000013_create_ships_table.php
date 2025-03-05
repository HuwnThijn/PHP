<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng ships
return new class extends Migration
{
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id('id_ship');
            $table->unsignedBigInteger('id_order')->unique();
            $table->text('address');
            $table->decimal('distance', 10, 2)->nullable();
            $table->decimal('shipping_fee', 10, 2);
            $table->enum('status', ['pending', 'shipping', 'delivered', 'failed'])->default('pending');
            $table->foreign('id_order')->references('id_order')->on('orders');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ships');
    }
};