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

        // Ambil seluruh data kriteria
        $kriteria = Kriteria::all();

        // Ambil kelompok tani sesuai kecamatan dan jenis tani
        $kelompokTani = KelompokTani::where('kecamatan_id', $kecamatanId)
            ->where('jenis_tani', $jenisTani)
            ->get();

        if ($kelompokTani->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada kelompok tani di kecamatan ini.');
        }

        $results = [];
        $totalScore = 0;
        $selectedIds = [];

        // Hitung nilai WPM (S_i)
        foreach ($kelompokTani as $kt) {
            $score = 1;

            foreach ($kriteria as $kr) {
                $kv = KriteriaValue::where('kelompok_tani_id', $kt->id)
                    ->where('kriteria_id', $kr->id)
                    ->first();

                $value = $kv ? max($kv->value, 0.0001) : 0.0001;

                if ($kr->jenis === 'cost') {
                    $score *= pow((1 / $value), $kr->bobot);
                } else {
                    $score *= pow($value, $kr->bobot);
                }
            }

            $results[] = [
                'kelompok_tani_id' => $kt->id,
                'nama' => $kt->nama,
                'ketua' => $kt->ketua,
                'desa' => $kt->desa,
                'score' => $score,
            ];

            $totalScore += $score;
            $selectedIds[] = $kt->id;
        }

        // Normalisasi dan ranking
        foreach ($results as &$result) {
            $result['V'] = ($totalScore == 0) ? 0 : ($result['score'] / $totalScore);
        }
        unset($result);

        usort($results, function ($a, $b) {
            return $b['V'] <=> $a['V'];
        });

        // Simpan ke DB
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
                    'nilai_wpm' => $result['V'],
                    'peringkat' => $peringkat,
                    'terpilih' => $terpilih,
                    'updated_at' => now(),
                    'created_at' => $createdAt,
                ]
            );
        }

        // Hapus data seleksi lama yang tidak termasuk dalam proses saat ini
        DB::table('seleksi')
            ->where('kecamatan_id', $kecamatanId)
            ->where('jenis_tani', $jenisTani)
            ->whereNotIn('kelompok_tani_id', $selectedIds)
            ->delete();

        return redirect('/hasil-seleksi?kecamatan_id=' . $kecamatanId)->with('success', 'Proses seleksi berhasil dilakukan!');
    }
}
