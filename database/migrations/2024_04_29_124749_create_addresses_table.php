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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('label', length: 30)->nullable(false);
            $table->string('street', length: 255)->nullable(false);
            $table->string('neighbourhood_number', length: 10)->nullable(false);
            $table->string('hamlet_number', length: 10)->nullable(false);
            $table->string('village', length: 10)->nullable(false);
            $table->string('urban_village', length: 255)->nullable(false);
            $table->string('sub_district', length: 255)->nullable(false);
            $table->unsignedSmallInteger('expedition_city_id')->nullable(false);
            $table->unsignedSmallInteger('expedition_province_id')->nullable(false);
            $table->string('postal_code', length: 10)->nullable(false);
            $table->string('note', length: 50)->nullable(false);
            $table->string('receiver_name', length: 50)->nullable(false);
            $table->string('telephone', length: 15)->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('expedition_city_id')->references('id')->on('expedition_cities');
            $table->foreign('expedition_province_id')->references('id')->on('expedition_provinces');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
