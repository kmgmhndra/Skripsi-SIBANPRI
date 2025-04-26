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
        Schema::create('kriteria_values', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('kriteria_id');
            // ->constrained('kriteria')->onDelete('cascade')
            $table->foreignId('kelompok_tani_id');
            // ->constrained('kelompok_tani')->onDelete('cascade')
            $table->decimal('value', 8, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_values');
    }
};
