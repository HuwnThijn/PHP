<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_verification_token')->nullable()->after('status');
            $table->string('avatar')->nullable()->after('email_verification_token');
            $table->timestamp('email_verified_at')->nullable()->after('avatar');
            $table->rememberToken()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verification_token');
            $table->dropColumn('avatar');
            $table->dropColumn('email_verified_at');
            $table->dropRememberToken();
        });
    }
}; 