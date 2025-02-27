<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tạo migration cho bảng prescriptions
return new class extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id('id_prescription');
            $table->unsignedBigInteger('id_medical_record');
            $table->string('medicine', 100);
            $table->string('dosage', 100);
            $table->string('frequency', 100);
            $table->integer('duration');
            $table->dateTime('prescribed_at')->nullable(); // Đặt nullable thay vì useCurrent()
            $table->foreign('id_medical_record')->references('id_medical_record')->on('medical_records');
            $table->timestamps(); // Chỉ sử dụng timestamps() của Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
};