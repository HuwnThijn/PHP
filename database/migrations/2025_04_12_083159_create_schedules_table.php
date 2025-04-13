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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->date('date'); // Ngày làm việc
            $table->time('start_time'); // Thời gian bắt đầu
            $table->time('end_time'); // Thời gian kết thúc
            $table->boolean('is_available')->default(true); // Trạng thái có sẵn sàng làm việc không
            $table->boolean('repeat_weekly')->default(false); // Có lặp lại hàng tuần không
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();

            // Foreign key
            $table->foreign('doctor_id')->references('id_user')->on('users')->onDelete('cascade');
            
            // Index
            $table->index(['date', 'is_available']);
            $table->index(['doctor_id', 'date']);
            $table->index(['repeat_weekly']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
