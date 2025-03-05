<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng ranks
return new class extends Migration
{
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->id('id_rank');
            $table->string('name', 50);
            $table->integer('min_points')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ranks');
    }
};