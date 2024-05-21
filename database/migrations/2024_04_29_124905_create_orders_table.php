<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('orders', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->unsignedMediumInteger('total_price')->nullable(false);
        $table->enum('status', ['NEW', 'PROCESSED', 'CONFIRMED', 'SENT', 'FINISHED', 'CANCELLED']);
        $table->unsignedBigInteger('address_id')->nullable(false);
        $table->unsignedBigInteger('user_id')->nullable(false);
        $table->unsignedSmallInteger('expedition_id')->nullable(false);
        $table->unsignedBigInteger('store_id')->nullable(false);
         $table->string('midtrans_token')->nullable();
        $table->string('receipt_number')->nullable();
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
