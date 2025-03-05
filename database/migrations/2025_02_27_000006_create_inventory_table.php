<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng inventory
return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('id_inventory');
            $table->unsignedBigInteger('id_cosmetic');
            $table->integer('quantity')->default(0);
            $table->string('supplier', 100)->nullable();
            $table->dateTime('last_updated')->nullable(); // Đặt nullable thay vì useCurrent()
            $table->timestamps(); // Chỉ sử dụng timestamps() của Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory');
    }
};