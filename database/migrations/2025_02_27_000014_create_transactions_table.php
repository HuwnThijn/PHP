<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng transactions
return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaction')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_order');
            $table->decimal('amount', 10, 2);
            $table->integer('points_earned')->default(0);
            $table->integer('points_used')->default(0);
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer']);
            $table->dateTime('transaction_date')->nullable(); // Đặt nullable thay vì useCurrent()
            $table->decimal('final_amount', 10, 2);
            $table->timestamps(); // Chỉ sử dụng timestamps() của Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};