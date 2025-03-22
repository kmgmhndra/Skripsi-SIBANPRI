<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\Kecamatan;

class SeleksiController extends Controller
{
    public function proses(Request $request)
    {
        // Validasi kecamatan
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);
        $kecamatanId = $request->input('kecamatan_id');

        // Ambil data kelompok tani berdasarkan kecamatan yang dipilih
        $kelompokTani = KelompokTani::where('kecamatan_id', $kecamatanId)->get();

        if ($kelompokTani->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada kelompok tani di kecamatan ini.');
        }

        // Ambil data kriteria beserta bobotnya (ROC)
        $kriteria = Kriteria::all();
        $results = [];

        foreach ($kelompokTani as $kt) {
            $score = 1;

            foreach ($kriteria as $kr) {
                $value = $kt->{$kr->atribut}; 

                if ($kr->jenis === 'cost') {
                    $value = ($value == 0) ? 0.0001 : $value; 
                    $score *= pow(1 / $value, $kr->bobot);
                } else {
                    $score *= pow($value, $kr->bobot);
                }
            }

            // Simpan hasil seleksi ke tabel `seleksi`
            \DB::table('seleksi')->updateOrInsert(
                ['kelompok_tani_id' => $kt->id],
                [
                    'kecamatan_id' => $kecamatanId,
                    'nilai' => $score,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // Tambahkan ke array results untuk ditampilkan
            $results[] = [
                'id' => $kt->id,
                'nama' => $kt->nama,
                'ketua' => $kt->ketua,
                'desa' => $kt->desa,
                'nilai' => $score,
            ];
        }

        // Urutkan berdasarkan nilai WPM (descending)
        usort($results, function ($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        // Ambil semua kecamatan untuk dropdown
        $kecamatan = Kecamatan::all();

        // Redirect ke halaman hasil seleksi dengan membawa data
        return redirect()->route('seleksi.index')->with([
            'results' => $results,
            'kecamatan' => $kecamatan,
            'success' => 'Proses seleksi berhasil dilakukan!',
        ]);
    }

}
