<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('kelompok_tani', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama kelompok tani
            $table->string('desa'); // Nama desa
            $table->string('ketua'); // Nama ketua kelompok tani
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->onDelete('cascade'); // Relasi ke tabel kecamatan
            $table->integer('simluhtan'); // 1 atau 5
            $table->integer('terpoligon'); // 1 atau 5
            $table->integer('bantuan_sebelumnya'); // 1 atau 5
            $table->decimal('dpi', 8, 2); // DPI (angka)
            $table->decimal('provitas', 8, 2); // Provitas (angka real)
            $table->enum('status', ['terpilih', 'tidak_terpilih'])->default('tidak_terpilih'); // Tambah status
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
