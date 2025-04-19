<?php

namespace Tests\Unit;

use App\Models\Kriteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RocKriteriaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_calculate_roc_weights_correctly()
    {
        // Persiapkan data kriteria
        Kriteria::create(['nama' => 'Simluhtan', 'urutan' => 1, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Lahan Terpoligon', 'urutan' => 2, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Bantuan Sebelumnya', 'urutan' => 3, 'jenis' => 'cost']);
        Kriteria::create(['nama' => 'DPI', 'urutan' => 4, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Provitas', 'urutan' => 5, 'jenis' => 'cost']);

        // Hitung bobot ROC
        Kriteria::hitungBobotROC();

        // Ambil semua kriteria yang sudah diurutkan
        $kriteria = Kriteria::orderBy('urutan')->get();

        // Periksa apakah bobot sudah dihitung dengan benar (perhitungan manual ROC)
        $this->assertEquals($kriteria[0]->bobot, 0.4567);
        $this->assertEquals($kriteria[1]->bobot, 0.2567);
        $this->assertEquals($kriteria[2]->bobot, 0.1567);
        $this->assertEquals($kriteria[3]->bobot, 0.0900);
        $this->assertEquals($kriteria[4]->bobot, 0.0400);
    }
}
