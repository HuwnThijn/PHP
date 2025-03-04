<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng users
return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user')->primary();
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_rank');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 20);
            $table->string('password', 255);
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->integer('points')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0.00);
            $table->dateTime('last_transaction')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('failed_appointments')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};