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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_ward')->nullable();
            $table->string('shipping_district')->nullable();
            $table->string('shipping_province')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'confirmed_at',
                'shipped_at',
                'delivered_at',
                'cancellation_reason',
                'payment_status',
                'subtotal',
                'shipping_fee',
                'tax',
                'discount',
                'transaction_id',
                'shipping_name',
                'shipping_phone',
                'shipping_address',
                'shipping_ward',
                'shipping_district',
                'shipping_province',
                'notes'
            ]);
        });
    }
}; 