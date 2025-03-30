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
        Schema::create('services', function (Blueprint $table) {
            $table->id('id_service');
            $table->string('name')->comment('Tên dịch vụ');
            $table->text('description')->nullable()->comment('Mô tả dịch vụ');
            $table->decimal('price', 10, 2)->default(0)->comment('Giá dịch vụ');
            $table->integer('duration')->default(30)->comment('Thời gian dịch vụ (phút)');
            $table->string('image')->nullable()->comment('Hình ảnh dịch vụ');
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
