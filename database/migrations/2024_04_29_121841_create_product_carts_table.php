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
        Schema::create('product_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->unsignedBigInteger('cart_id')->nullable(false);
            $table->unsignedInteger('sub_total_price')->nullable(false);
            $table->unsignedInteger('quantity')->nullable(false);
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('cart_id')->references('id')->on('carts');
            $table->unique(['product_id', 'cart_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_carts');
    }
};
