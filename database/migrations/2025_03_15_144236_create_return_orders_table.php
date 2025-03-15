<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->text('reason');
            $table->enum('return_type', ['refund', 'exchange']);
            $table->unsignedBigInteger('id_status')->default(1); // 1: pending, 2: completed, 3: rejected
            $table->decimal('total_refund', 10, 2)->default(0);
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('order_id')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('processed_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_orders');
    }
}
