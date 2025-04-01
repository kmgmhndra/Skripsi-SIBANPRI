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
        Schema::create('sub_laporans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('laporan_id');
            $table->string('nama_kelompok_tani');
            $table->string('nama_ketua');
            $table->string('nama_desa');
            $table->decimal('nilai_wpm', 8, 4); // Menyimpan nilai hasil seleksi
            $table->integer('peringkat'); // Menyimpan nilai hasil seleksi
            $table->foreignId('kelompok_tani_id');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_laporans');
    }
};
