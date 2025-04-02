<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\KriteriaValue;
use Illuminate\Support\Facades\DB;

class SeleksiController extends Controller
{
    public function proses(Request $request)
    {
        // Validasi input kecamatan_id
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'jenis_tani' => 'required',
        ]);

        $kecamatanId = $request->input('kecamatan_id');
        $jenisTani = $request->input('jenis_tani');

        // Ambil seluruh data kriteria (dengan bobot, nama, dan jenis: cost/benefit)
        $kriteria = Kriteria::all();

        // Ambil data kelompok tani sesuai kecamatan
        $kelompokTani = KelompokTani::where('kecamatan_id', $kecamatanId)->where('jenis_tani', $jenisTani)->get();
        if ($kelompokTani->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada kelompok tani di kecamatan ini.');
        }

        $results = [];
        $totalScore = 0;

        // === Tahap Perhitungan WP (Menghitung S_i) ===
        foreach ($kelompokTani as $kt) {
            $score = 1;

            foreach ($kriteria as $kr) {
                $kv = KriteriaValue::where('kelompok_tani_id', $kt->id)
                    ->where('kriteria_id', $kr->id)
                    ->first();

                // Pastikan nilai tidak 0 atau null, gunakan 0.0001 sebagai nilai minimal
                $value = $kv ? max($kv->value, 0.0001) : 0.0001;

                if ($kr->jenis === 'cost') {
                    $score *= pow((1 / $value), $kr->bobot);
                } else { // benefit
                    $score *= pow($value, $kr->bobot);
                }

                // dump("Kelompok: {$kt->nama}, Kriteria: {$kr->nama}, Nilai: {$value}, Bobot: {$kr->bobot}, Score sementara: {$score}");
            }

            // Cek setiap hasil per kelompok
            // dump("Kelompok: {$kt->nama}, Total S_i: {$score}");

            $results[] = [
                'kelompok_tani_id' => $kt->id,
                'nama' => $kt->nama,
                'ketua' => $kt->ketua,
                'desa' => $kt->desa,
                'score' => $score, // Nilai S_i
            ];

            $totalScore += $score;
        }

        // Cek total S_i keseluruhan sebelum normalisasi
        // dump("Total S_i Keseluruhan: {$totalScore}");

        // === Tahap Normalisasi WP (Menghitung V_i) ===
        foreach ($results as &$result) {
            $result['V'] = ($totalScore == 0) ? 0 : ($result['score'] / $totalScore);
        }
        unset($result); // Hapus referensi array

        // Urutkan hasil berdasarkan nilai V terbesar (ranking)
        usort($results, function ($a, $b) {
            return $b['V'] <=> $a['V'];
        });

        // === Simpan ke Database ===
        foreach ($results as $index => $result) {
            $peringkat = $index + 1;

            $existing = DB::table('seleksi')
                ->where('kelompok_tani_id', $result['kelompok_tani_id'])
                ->first();

            $terpilih = $existing ? $existing->terpilih : false;
            $createdAt = $existing ? $existing->created_at : now();

            DB::table('seleksi')->updateOrInsert(
                ['kelompok_tani_id' => $result['kelompok_tani_id']],
                [
                    'jenis_tani' => $jenisTani,
                    'kecamatan_id' => $kecamatanId,
                    'nama_kelompok_tani' => $result['nama'],
                    'ketua' => $result['ketua'],
                    'desa' => $result['desa'],
                    'nilai_wpm' => $result['V'], // Simpan nilai V, bukan S_i
                    'peringkat' => $peringkat,
                    'terpilih' => $terpilih, // Pertahankan nilai sebelumnya
                    'updated_at' => now(),
                    'created_at' => $createdAt,
                ]
            );
        }

        return redirect('/hasil-seleksi?kecamatan_id=' . $kecamatanId)->with('success', 'Proses seleksi berhasil dilakukan!');
    }
}
