<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\Kecamatan;

class HasilSeleksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID kecamatan dari request (default 1, bisa Anda ganti sesuai kebutuhan)
        $kecamatanId = $request->input('kecamatan_id', 1);

        // Ambil semua data kecamatan untuk dropdown
        $kecamatan = Kecamatan::all();

        // Ambil data kelompok tani berdasarkan kecamatan
        $kelompokTani = KelompokTani::where('kecamatan_id', $kecamatanId)->get();

        // Ambil bobot kriteria dari tabel kriteria (hasil ROC)
        // NOTE: Pastikan urutan (kunci) cocok dengan field yang diakses di perhitungan
        $bobotKriteria = Kriteria::pluck('bobot', 'urutan')->toArray();

        // Lakukan perhitungan WPM untuk setiap kelompok tani
        $hasilSeleksi = $kelompokTani->map(function ($tani) use ($bobotKriteria) {
            // Pastikan kunci seperti 'simluhtan', 'terpoligon', dsb. memang sesuai dengan 'urutan' di DB
            // Jika di DB hanya ada urutan numeric, sebaiknya gunakan 'nama' kriteria sebagai kunci
            $nilaiWPM = pow($tani->simluhtan, $bobotKriteria['simluhtan'] ?? 1)
                      * pow($tani->terpoligon, $bobotKriteria['terpoligon'] ?? 1)
                      * pow($tani->bantuan_sebelumnya, $bobotKriteria['bantuan_sebelumnya'] ?? 1)
                      * pow($tani->dpi, $bobotKriteria['dpi'] ?? 1)
                      * pow($tani->provitas, $bobotKriteria['provitas'] ?? 1);

            return [
                'id'         => $tani->id,
                'nama'       => $tani->nama,
                'ketua' => $tani->ketua,
                'desa'       => $tani->desa,
                'nilai'      => $nilaiWPM,
            ];
        });

        // Urutkan berdasarkan nilai WPM (dari terbesar ke terkecil)
        $hasilSeleksi = $hasilSeleksi->sortByDesc('nilai')->values()->toArray();

        // Pastikan nama view sesuai dengan file Blade Anda:
        // Misalnya: resources/views/hasil-seleksi/index.blade.php
        // Ganti 'seleksi.index' menjadi 'hasil-seleksi.index' jika file Blade-nya ada di folder 'hasil-seleksi'
        return view('seleksi.index', compact('hasilSeleksi', 'kecamatanId', 'kecamatan'));
    }

    public function simpan(Request $request)
    {
        // Logika penyimpanan data hasil seleksi
        // Contoh: menyimpan ke tabel 'riwayat_seleksi' atau update status KelompokTani
        // ...

        return redirect()->back()->with('success', 'Hasil seleksi berhasil disimpan.');
    }
}
