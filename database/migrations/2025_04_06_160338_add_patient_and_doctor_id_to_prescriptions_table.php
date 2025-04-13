<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Thêm cột id_patient và id_doctor
            $table->unsignedBigInteger('id_patient')->nullable()->after('id_medical_record');
            $table->unsignedBigInteger('id_doctor')->nullable()->after('id_patient');
            
            // Thêm khóa ngoại
            $table->foreign('id_patient')->references('id_user')->on('users');
            $table->foreign('id_doctor')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['id_patient']);
            $table->dropForeign(['id_doctor']);
            
            // Xóa cột
            $table->dropColumn(['id_patient', 'id_doctor']);
        });
    }
};
