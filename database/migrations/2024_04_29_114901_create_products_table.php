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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug', length: 60)->nullable(false);
            $table->string('name', length: 50)->nullable(false);
            $table->enum('condition', ['NEW', 'SECOND'])->default('NEW')->nullable(false);
            $table->text('description')->nullable(false);
            $table->unsignedInteger('price')->nullable(false);
            $table->unsignedSmallInteger('minimum_order')->nullable(false);
            $table->enum('status', ['ACTIVE', 'NON_ACTIVE'])->default('ACTIVE')->nullable(false);
            $table->unsignedMediumInteger('stock')->nullable(false);
            $table->string('sku', length: 50)->nullable(false);
            $table->unsignedSmallInteger('weight')->nullable(false);
            $table->unsignedSmallInteger('width')->nullable(false);
            $table->unsignedSmallInteger('height')->nullable(false);
            $table->unsignedBigInteger('store_id')->nullable(false);
            $table->foreign('store_id')->references('id')->on('stores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
