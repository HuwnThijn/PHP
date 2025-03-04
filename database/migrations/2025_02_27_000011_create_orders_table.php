<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng orders
return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order')->unique();
            $table->unsignedBigInteger('id_user');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer']);
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps(); // Chỉ sử dụng timestamps() của Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};