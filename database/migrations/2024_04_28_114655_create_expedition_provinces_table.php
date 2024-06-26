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
      Schema::create('expedition_provinces', function (Blueprint $table) {
        $table->unsignedSmallInteger('id')->primary();
        $table->string('name', length: 255)->nullable(false);
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('expedition_provinces');
    }
  };
