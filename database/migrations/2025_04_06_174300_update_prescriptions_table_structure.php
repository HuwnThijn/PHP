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
            // Thêm cột tổng tiền và người xử lý
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            
            // Tạo khóa ngoại cho processed_by
            $table->foreign('processed_by')->references('id_user')->on('users');
            
            // Loại bỏ các cột cũ
            $table->dropColumn(['medicine', 'dosage', 'frequency', 'duration', 'prescribed_at']);
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
            // Xóa các cột mới thêm
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['total_amount', 'processed_by', 'processed_at']);
            
            // Thêm lại các cột cũ
            $table->string('medicine', 100);
            $table->string('dosage', 100);
            $table->string('frequency', 100);
            $table->integer('duration');
            $table->dateTime('prescribed_at')->nullable();
        });
    }
}; 