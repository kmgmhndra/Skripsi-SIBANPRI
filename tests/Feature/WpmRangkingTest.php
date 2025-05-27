<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Kecamatan;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\KriteriaValue;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class WpmRangkingTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware; 

    public function test_wpm_ranking_is_calculated_correctly()
    {
        // Simulasi tahun sesi
        Session::put('tahun', 2025);

        // 1. Buat kecamatan
        $kecamatan = Kecamatan::create([
            'nama' => 'Kecamatan Test'
        ]);

        // 2. Buat kriteria dengan bobot ROC
        $k1 = Kriteria::create(['nama' => 'Simluhtan', 'urutan' => 1, 'jenis' => 'benefit', 'bobot' => 0.4567]);
        $k2 = Kriteria::create(['nama' => 'Lahan Terpoligon', 'urutan' => 2, 'jenis' => 'benefit', 'bobot' => 0.2567]);
        $k3 = Kriteria::create(['nama' => 'Bantuan Sebelumnya', 'urutan' => 3, 'jenis' => 'cost', 'bobot' => 0.1567]);
        $k4 = Kriteria::create(['nama' => 'DPI', 'urutan' => 4, 'jenis' => 'benefit', 'bobot' => 0.0900]);
        $k5 = Kriteria::create(['nama' => 'Provitas', 'urutan' => 5, 'jenis' => 'cost', 'bobot' => 0.0400]);

        // 3. Buat 3 kelompok tani
        $kt1 = KelompokTani::create([
            'nama' => 'Kelompok A',
            'ketua' => 'Pak A',
            'desa' => 'Desa A',
            'kecamatan_id' => $kecamatan->id,
            'jenis_tani' => 'Padi',
            'tahun' => 2025,
        ]);

        $kt2 = KelompokTani::create([
            'nama' => 'Kelompok B',
            'ketua' => 'Pak B',
            'desa' => 'Desa B',
            'kecamatan_id' => $kecamatan->id,
            'jenis_tani' => 'Padi',
            'tahun' => 2025,
        ]);

        $kt3 = KelompokTani::create([
            'nama' => 'Kelompok C',
            'ketua' => 'Pak C',
            'desa' => 'Desa C',
            'kecamatan_id' => $kecamatan->id,
            'jenis_tani' => 'Padi',
            'tahun' => 2025,
        ]);

        // 4. Buat nilai kriteria
        // Kelompok A (nilai sedang)
        KriteriaValue::create(['kelompok_tani_id' => $kt1->id, 'kriteria_id' => $k1->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt1->id, 'kriteria_id' => $k2->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt1->id, 'kriteria_id' => $k3->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt1->id, 'kriteria_id' => $k4->id, 'value' => 3]);
        KriteriaValue::create(['kelompok_tani_id' => $kt1->id, 'kriteria_id' => $k5->id, 'value' => 18]);

        // Kelompok B (nilai tinggi benefit, rendah cost)
        KriteriaValue::create(['kelompok_tani_id' => $kt2->id, 'kriteria_id' => $k1->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt2->id, 'kriteria_id' => $k2->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt2->id, 'kriteria_id' => $k3->id, 'value' => 1]); 
        KriteriaValue::create(['kelompok_tani_id' => $kt2->id, 'kriteria_id' => $k4->id, 'value' => 4]);
        KriteriaValue::create(['kelompok_tani_id' => $kt2->id, 'kriteria_id' => $k5->id, 'value' => 20]); 

        // Kelompok C (nilai tinggi benefit, tinggi cost)
        KriteriaValue::create(['kelompok_tani_id' => $kt3->id, 'kriteria_id' => $k1->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt3->id, 'kriteria_id' => $k2->id, 'value' => 5]);
        KriteriaValue::create(['kelompok_tani_id' => $kt3->id, 'kriteria_id' => $k3->id, 'value' => 5]); 
        KriteriaValue::create(['kelompok_tani_id' => $kt3->id, 'kriteria_id' => $k4->id, 'value' => 6]);
        KriteriaValue::create(['kelompok_tani_id' => $kt3->id, 'kriteria_id' => $k5->id, 'value' => 30]); 

        // 5. Kirim request ke endpoint seleksi
        $response = $this->post('/seleksi-proses', [
            'kecamatan_id' => $kecamatan->id,
            'jenis_tani' => 'Padi',
        ]);

        // 6. Periksa redirect berhasil
        $response->assertRedirect();

        // 7. Pastikan data seleksi disimpan
        $this->assertDatabaseHas('seleksi', ['nama_kelompok_tani' => 'Kelompok A']);
        $this->assertDatabaseHas('seleksi', ['nama_kelompok_tani' => 'Kelompok B']);
        $this->assertDatabaseHas('seleksi', ['nama_kelompok_tani' => 'Kelompok C']);

        // 8. Ambil data dan pastikan ranking benar (B > C > A)
        $ranking = DB::table('seleksi')
            ->where('kecamatan_id', $kecamatan->id)
            ->where('jenis_tani', 'Padi')
            ->orderBy('peringkat')
            ->pluck('nama_kelompok_tani')
            ->toArray();

            $this->assertEquals(['Kelompok B', 'Kelompok C', 'Kelompok A'], $ranking);
    }
}
