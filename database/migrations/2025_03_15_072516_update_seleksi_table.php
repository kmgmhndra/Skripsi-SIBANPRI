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
        Schema::table('seleksi', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->onDelete('cascade');
            $table->string('nama_kelompok_tani', 50);
            $table->foreignId('kelompok_tani_id');
            $table->string('ketua', 50);
            $table->string('desa', 50);
            $table->year('tahun');
            $table->decimal('nilai_wpm', 8, 4);
            $table->integer('peringkat');
            $table->boolean('terpilih')->default(false);
            $table->enum('jenis_tani', ['Padi', 'Palawija', 'Pupuk']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seleksi', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn([
                'kecamatan_id',
                'nama_kelompok_tani',
                'ketua',
                'desa',
                'nilai_wpm',
                'peringkat',
                'terpilih'
            ]);
        });
    }
};
