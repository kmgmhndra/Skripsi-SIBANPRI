<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('kelompok_tani', function (Blueprint $table) {
            $table->id();

            $table->string('nama', 50); // Nama kelompok tani
            $table->string('desa', 50); // Nama desa
            $table->string('ketua', 50); // Nama ketua kelompok tani
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->onDelete('cascade'); // Relasi ke tabel kecamatan
            $table->enum('status', ['terpilih', 'tidak_terpilih'])->default('tidak_terpilih'); // Tambah status
            $table->enum('jenis_tani', ['Padi', 'Palawija', 'Pupuk']); // Jenis Tani
            $table->year('tahun');

            $table->timestamps();
        });
    }

    /**
     * Hapus migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_tani');
    }
};
