<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng order_items
return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_item');
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_cosmetic');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->foreign('id_order')->references('id_order')->on('orders');
            $table->foreign('id_cosmetic')->references('id_cosmetic')->on('cosmetics');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};