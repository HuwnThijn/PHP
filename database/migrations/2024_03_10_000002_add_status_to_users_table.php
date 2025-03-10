<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột status
            $table->enum('status', ['active', 'temporary_locked', 'permanent_locked'])->default('active');
            
            // Xóa các cột cũ nếu tồn tại
            if (Schema::hasColumn('users', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
            if (Schema::hasColumn('users', 'locked_until')) {
                $table->dropColumn('locked_until');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_until')->nullable();
        });
    }
}; 