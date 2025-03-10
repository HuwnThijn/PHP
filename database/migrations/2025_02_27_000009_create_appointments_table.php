<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng appointments
return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id_appointment');
            $table->unsignedBigInteger('id_patient');
            $table->unsignedBigInteger('id_doctor');
            $table->dateTime('appointment_time');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no-show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->foreign('id_patient')->references('id_user')->on('users');
            $table->foreign('id_doctor')->references('id_user')->on('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};