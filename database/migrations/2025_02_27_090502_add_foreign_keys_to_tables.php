<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Thêm khóa ngoại cho bảng users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_role')->references('id_role')->on('roles');
            $table->foreign('id_rank')->references('id_rank')->on('ranks');
        });

        // Thêm khóa ngoại cho bảng cosmetics
        Schema::table('cosmetics', function (Blueprint $table) {
            $table->foreign('id_category')->references('id_category')->on('categories');
        });

        // Thêm khóa ngoại cho bảng inventory
        Schema::table('inventory', function (Blueprint $table) {
            $table->foreign('id_cosmetic')->references('id_cosmetic')->on('cosmetics');
        });

        // Thêm khóa ngoại cho bảng medical_records
        Schema::table('medical_records', function (Blueprint $table) {
            $table->foreign('id_patient','fk_medical_records_patient')->references('id_user')->on('users');
            $table->foreign('id_doctor','fk_medical_records_doctor')->references('id_user')->on('users');
        });

        // Thêm khóa ngoại cho bảng prescriptions
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreign('id_medical_record','fk__prescriptions_id_medical_records')->references('id_medical_record')->on('medical_records');
        });

        // Thêm khóa ngoại cho bảng appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('id_patient','fk_appointments_patient')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_doctor','fk_appointments_doctor1')->references('id_user')->on('users')->onDelete('cascade');
        });

        // Thêm khóa ngoại cho bảng reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('id_cosmetic','fk_reviews_id_cosmetic')->references('id_cosmetic')->on('cosmetics');
            $table->foreign('id_user','fk_reviews_id_user')->references('id_user')->on('users');
        });

        // Thêm khóa ngoại cho bảng orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('id_user','fk_orfers_id_user')->references('id_user')->on('users');
        });

        // Thêm khóa ngoại cho bảng order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('id_order','fk_order_items_id_order')->references('id_order')->on('orders');
            $table->foreign('id_cosmetic','fk_order_items_id_cosmetic')->references('id_cosmetic')->on('cosmetics');
        });

        // Thêm khóa ngoại cho bảng ships
        Schema::table('ships', function (Blueprint $table) {
            $table->foreign('id_order','fk_ships_id_orders')->references('id_order')->on('orders');
        });

        // Thêm khóa ngoại cho bảng transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('id_user','fk_transactions_id_user')->references('id_user')->on('users');
            $table->foreign('id_order','fk_transactions_id_order')->references('id_order')->on('orders');
        });
    }

    public function down()
    {
        // Xóa khóa ngoại theo thứ tự ngược lại
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropForeign(['id_order']);
        });

        Schema::table('ships', function (Blueprint $table) {
            $table->dropForeign(['id_order']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['id_order']);
            $table->dropForeign(['id_cosmetic']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['id_cosmetic']);
            $table->dropForeign(['id_user']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['id_patient']);
            $table->dropForeign(['id_doctor']);
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['id_medical_record']);
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['id_patient']);
            $table->dropForeign(['id_doctor']);
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['id_cosmetic']);
        });

        Schema::table('cosmetics', function (Blueprint $table) {
            $table->dropForeign(['id_category']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_role']);
            $table->dropForeign(['id_rank']);
        });
    }
};