<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng cosmetics
return new class extends Migration
{
    public function up()
    {
        Schema::create('cosmetics', function (Blueprint $table) {
            $table->id('id_cosmetic')->unique();
            $table->unsignedBigInteger('id_category');
            $table->string('name', 200);
            $table->decimal('price', 10, 2);
            $table->float('rating')->default(0);
            $table->boolean('isHidden')->default(false);
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cosmetics');
    }
};