<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\Kecamatan;
use App\Models\Seleksi;

class HasilSeleksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID kecamatan dari request (default 1, bisa Anda ganti sesuai kebutuhan)
        $kecamatanId = $request->input('kecamatan_id', 1);

        // Ambil semua data kecamatan untuk dropdown
        $kecamatan = Kecamatan::all();

        // Ambil data kelompok tani berdasarkan kecamatan
        $seleksis = Seleksi::where('kecamatan_id', $kecamatanId)->get();

        // Urutkan berdasarkan nilai WPM (dari terbesar ke terkecil)
        $hasilSeleksi = $seleksis->sortByDesc('nilai_wpm')->values()->toArray();

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
