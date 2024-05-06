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
            Schema::create('expedition_cities', function (Blueprint $table) {
                $table->unsignedSmallInteger('id')->primary();
                $table->enum('type', ['KOTA', 'KABUPATEN'])->nullable(false);
                $table->string('name', length: 255)->nullable(false);
                $table->string('postal_code', length: 10)->nullable(false);
                $table->unsignedSmallInteger('expedition_province_id')->nullable(false);
                $table->foreign('expedition_province_id')->references('id')->on('expedition_provinces');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('expedition_cities');
        }
    };
