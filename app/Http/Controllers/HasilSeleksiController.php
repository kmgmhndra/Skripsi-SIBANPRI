<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\Kecamatan;
use App\Models\Seleksi;
use App\Models\Laporan;
use App\Models\SubLaporan;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HasilSeleksiController extends Controller
{
    public function index(Request $request)
    {
        $kecamatanId = $request->input('kecamatan_id');
        $jenisTani = Session::get('jenis_tani');

        // dd(request('kecamatan_id'));

        $kecamatan = Kecamatan::all();
        $tahun = Session::get('tahun');


        $seleksis = Seleksi::where('kecamatan_id', $kecamatanId)->where('jenis_tani', $jenisTani)->where('tahun', $tahun)->get();

        $hasilSeleksi = $seleksis->sortByDesc('nilai_wpm')->values()->toArray();

        return view('seleksi.index', compact('hasilSeleksi', 'kecamatanId', 'kecamatan'));
    }

    public function simpan(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kecamatan_id' => 'required',
            'kelompok_tani_id' => 'required|array',
        ]);


        $seleksi_data = Seleksi::whereIn('kelompok_tani_id', $request->kelompok_tani_id)->get();


        // Check if any data was found
        if ($seleksi_data->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data seleksi ditemukan untuk kelompok tani yang dipilih.');
        }

        $kecamatan = Kecamatan::findOrFail($request->kecamatan_id);
        $tahun = Session::get('tahun');

        // Create the main report
        $laporan = Laporan::create([
            'nama_laporan' => 'Laporan ' . $kecamatan->nama, // Fixed string concatenation (using . instead of +)
            'kecamatan' => $kecamatan->nama,
            'tanggal_seleksi' => Carbon::now(),    
            'jumlah_kelompok_tani' => $seleksi_data->count(),
            'jenis_tani' => $seleksi_data->first()->jenis_tani,
            'tahun' => $tahun,
            'user_id' => Auth::id()
        ]);

        // Create sub-reports

        foreach ($seleksi_data as $data) {

            $kelompokTani = KelompokTani::find($data->kelompok_tani_id);
            $kelompokTani->status = 'terpilih';
            $kelompokTani->save();

            SubLaporan::create([
                'laporan_id' => $laporan->id,
                'nama_kelompok_tani' => $data->nama_kelompok_tani,
                'nama_ketua' => $data->ketua,
                'nama_desa' => $data->desa,
                'nilai_wpm' => $data->nilai_wpm,
                'peringkat' => $data->peringkat,
                'kelompok_tani_id' => $data->kelompok_tani_id
            ]);
        }

        return redirect('/laporan')->with('success', 'Hasil seleksi berhasil disimpan.');
    }
}
