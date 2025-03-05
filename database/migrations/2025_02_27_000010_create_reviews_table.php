<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng reviews
return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->unsignedBigInteger('id_cosmetic');
            $table->unsignedBigInteger('id_user');
            $table->text('comment')->nullable();
            $table->integer('rating');
            $table->foreign('id_cosmetic')->references('id_cosmetic')->on('cosmetics');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->timestamps(); // Chỉ sử dụng timestamps() của Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};