<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\Kecamatan;
use App\Models\KriteriaValue;
// use App\Models\Seleksi;

class SeleksiController extends Controller
{
    // public function proses(Request $request)
    // {
    //     // dd($request->kecamatan_id);

    //     // Validasi kecamatan
    //     $request->validate([
    //         'kecamatan_id' => 'required|exists:kecamatan,id',
    //     ]);
    //     $kecamatanId = $request->input('kecamatan_id');

    //     // Ambil data kelompok tani berdasarkan kecamatan yang dipilih
    //     $kelompokTani = KelompokTani::where('kecamatan_id', $kecamatanId)->get();
    //     $kriteriavalue = KriteriaValue::all(); 
    //     if ($kelompokTani->isEmpty()) {
    //         return redirect()->back()->with('error', 'Tidak ada kelompok tani di kecamatan ini.');
    //     }

    //     // Ambil data kriteria beserta bobotnya (ROC)
    //     $kriteria = Kriteria::all();
    //     $results = [];

    //     foreach ($kelompokTani as $kt) {
    //         $score = 1;

    //         foreach ($kriteria as $kr) {
    //             $value = $kt->{$kr->atribut}; 

    //             if ($kr->jenis === 'cost') {
    //                 $value = ($value == 0) ? 0.0001 : $value; 
    //                 $score *= pow(1 / $value, $kr->bobot);
    //             } else {
    //                 $score *= pow($value, $kr->bobot);
    //             }
    //         }

    //         // Simpan hasil seleksi ke tabel `seleksi`
    //         \DB::table('seleksi')->updateOrInsert(
    //             ['kelompok_tani_id' => $kt->id],
    //             [
    //                 'kecamatan_id' => $kecamatanId,
    //                 'nilai' => $score,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]
    //         );

    //         // Tambahkan ke array results untuk ditampilkan
    //         $results[] = [
    //             'id' => $kt->id,
    //             'nama' => $kt->nama,
    //             'ketua' => $kt->ketua,
    //             'desa' => $kt->desa,
    //             'nilai' => $score,
    //         ];
    //     }

    //     // Urutkan berdasarkan nilai WPM (descending)
    //     usort($results, function ($a, $b) {
    //         return $b['nilai'] <=> $a['nilai'];
    //     });

    //     // Ambil semua kecamatan untuk dropdown
    //     $kecamatan = Kecamatan::all();

    //     // Redirect ke halaman hasil seleksi dengan membawa data
    //     return redirect()->route('seleksi.index')->with([
    //         'results' => $results,
    //         'kecamatan' => $kecamatan,
    //         'success' => 'Proses seleksi berhasil dilakukan!',
    //     ]);
    // }


   public function proses(Request $request)
{
    // Validasi input kecamatan_id
    $request->validate([
        'kecamatan_id' => 'required|exists:kecamatan,id',
    ]);
    $kecamatanId = $request->input('kecamatan_id');

    // Ambil seluruh data kriteria (dengan bobot, nama, dan jenis: cost/benefit)
    $kriteria = Kriteria::all();
    
    // Ambil data kelompok tani sesuai kecamatan
    $kelompokTani = KelompokTani::where('kecamatan_id', $kecamatanId)->get();
    if ($kelompokTani->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada kelompok tani di kecamatan ini.');
    }
    
    $results = [];

    // Proses perhitungan WP untuk tiap kelompok tani
    foreach ($kelompokTani as $kt) {
        $score = 1;
        
        // Untuk tiap kriteria, ambil nilai dari KriteriaValue dan hitung faktor perkalian
        foreach ($kriteria as $kr) {
            // Ambil nilai untuk kriteria ini pada kelompok tani saat ini
            $kv = KriteriaValue::where('kelompok_tani_id', $kt->id)
                ->where('kriteria_id', $kr->id)
                ->first();
            
            // Jika tidak ada nilai, asumsikan 0 (atau bisa disesuaikan)
            $value = $kv ? $kv->value : 0;
            
            // Untuk kriteria cost, gunakan nilai terbalik (hindari pembagian nol)
            if ($kr->jenis === 'cost') {
                $value = ($value == 0) ? 0.0001 : $value;
                $score *= pow((1 / $value), $kr->bobot);
            } else { // benefit
                $score *= pow($value, $kr->bobot);
            }
        }
        
        // Simpan hasil perhitungan ke array
        $results[] = [
            'kelompok_tani_id' => $kt->id,
            'nama'             => $kt->nama,
            'ketua'            => $kt->ketua,
            'desa'             => $kt->desa,
            'score'            => $score,
        ];
    }
    
    // Urutkan hasil berdasarkan score secara descending (nilai tertinggi mendapat peringkat 1)
    usort($results, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    // Simpan hasil ke tabel 'seleksi' dengan peringkat dan field lainnya
    foreach ($results as $index => $result) {
        $peringkat = $index + 1;
        
        \DB::table('seleksi')->updateOrInsert(
            // Kondisi: berdasarkan kelompok tani (bisa disesuaikan jika ada kunci unik lain)
            ['kelompok_tani_id' => $result['kelompok_tani_id']],
            [
                'kecamatan_id'         => $kecamatanId,
                'nama_kelompok_tani'   => $result['nama'],
                'ketua'                => $result['ketua'],
                'desa'                 => $result['desa'],
                'nilai_wpm'            => $result['score'],
                'peringkat'            => $peringkat,
                'terpilih'             => false, // default: belum terpilih
                'updated_at'           => now(),
                'created_at'           => now(),
            ]
        );
    }

    // Redirect ke halaman hasil seleksi (atau tampilkan pesan sukses)
    return redirect('/hasil-seleksi')->with('success', 'Proses seleksi berhasil dilakukan!');
}

    
     

}
