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
        Schema::table('appointments', function (Blueprint $table) {
            // Thêm khóa ngoại đến bảng services
            $table->unsignedBigInteger('id_service')->nullable()->after('id_doctor');
            $table->foreign('id_service')->references('id_service')->on('services');
            
            // Thay đổi id_patient thành nullable để hỗ trợ khách không đăng nhập
            $table->unsignedBigInteger('id_patient')->nullable()->change();
            
            // Thêm các trường cho khách không đăng nhập
            $table->string('guest_name', 100)->nullable()->after('id_service');
            $table->string('guest_email', 100)->nullable()->after('guest_name');
            $table->string('guest_phone', 20)->nullable()->after('guest_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['id_service']);
            $table->dropColumn('id_service');
            $table->dropColumn('guest_name');
            $table->dropColumn('guest_email');
            $table->dropColumn('guest_phone');
            
            // Revert id_patient to not nullable
            $table->unsignedBigInteger('id_patient')->nullable(false)->change();
        });
    }
};
