<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa cột status cũ
            $table->dropColumn('status');
        });

        Schema::table('users', function (Blueprint $table) {
            // Tạo lại cột status với kiểu ENUM
            $table->enum('status', ['active', 'temporary_locked', 'permanent_locked'])->default('active');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['active', 'temporary_locked', 'permanent_locked'])->default('active');
        });
    }
}; 