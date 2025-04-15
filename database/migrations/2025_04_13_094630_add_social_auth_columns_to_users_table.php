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
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột provider nếu chưa có
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('password');
            }
            
            // Thêm cột provider_id nếu chưa có
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
            }
            
            // Thêm cột avatar nếu chưa có
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('provider_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Chỉ xóa cột nếu chúng tồn tại
            $columns = [];
            
            if (Schema::hasColumn('users', 'provider')) {
                $columns[] = 'provider';
            }
            
            if (Schema::hasColumn('users', 'provider_id')) {
                $columns[] = 'provider_id';
            }
            
            if (Schema::hasColumn('users', 'avatar')) {
                $columns[] = 'avatar';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
