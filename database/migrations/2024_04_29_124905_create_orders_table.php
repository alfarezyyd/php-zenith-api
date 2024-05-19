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
            $table->unsignedMediumInteger('total_price')->nullable(false);
            $table->enum('status', [ 'PROCESSED', 'CONFIRMED', 'SENT', 'FINISHED', 'CANCELLED']);
            $table->enum('payment_method', ['CASH_ON_DELIVERY' , 'TRANSFER'])->nullable();
            $table->unsignedBigInteger('address_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedSmallInteger('expedition_id')->nullable(false);
            $table->foreign('expedition_id')->references('id')->on('expeditions');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
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
