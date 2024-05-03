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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 60)->nullable(false);
            $table->string('domain', length: 24)->nullable(false);
            $table->string('slogan', length: 48)->nullable(false);
            $table->string('location_name', length: 25)->nullable(false);
            $table->string('city', length: 50)->nullable(false);
            $table->string('zip_code', length: 10)->nullable(false);
            $table->string('detail', length: 200)->nullable(false);
            $table->string('description', length: 140)->nullable(false);
            $table->string('image_path', length: 255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
