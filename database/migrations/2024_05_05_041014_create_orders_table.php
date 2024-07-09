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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('driver_id')->unsigned();
            $table->json('items')->nullable();
            $table->json('from_address')->nullable();
            $table->json('to_address')->nullable();
            $table->double('shipping_cost')->default(0.0);
            $table->bigInteger('order_status_id')->unsigned();
            $table->timestamp('driver_accept_at')->nullable();
            $table->timestamp('complete_at')->nullable();
            $table->text('user_note')->nullable();
            $table->json('receiver')->nullable();
            $table->double('driver_rate')->nullable();
            $table->double('distance')->default(0.0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('order_status_id')->references('id')->on('order_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};