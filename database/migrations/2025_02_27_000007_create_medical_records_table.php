<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng medical_records
return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('id_medical_record');
            $table->unsignedBigInteger('id_patient');
            $table->unsignedBigInteger('id_doctor');
            $table->text('diagnosis')->nullable();
            $table->text('notes')->nullable();
            $table->string('pdf_url', 255)->nullable();
            $table->foreign('id_patient')->references('id_user')->on('users');
            $table->foreign('id_doctor')->references('id_user')->on('users');
            $table->timestamps(); // Chỉ sử dụng timestamps() của Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};