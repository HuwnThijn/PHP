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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_id')->nullable()->after('payment_method');
            $table->string('payment_status')->nullable()->after('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_id');
            $table->dropColumn('payment_status');
        });
    }
};
