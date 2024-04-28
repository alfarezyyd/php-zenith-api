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
