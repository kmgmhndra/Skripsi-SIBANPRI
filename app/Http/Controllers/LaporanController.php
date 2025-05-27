<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\SubLaporan;
use App\Models\KelompokTani;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class LaporanController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'nama_laporan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tanggal_seleksi' => 'required|date',
            'jumlah_kelompok_tani' => 'required|integer',
            'jenis_tani' => 'required|string',
            'tahun' => 'required|digits:4|integer',
        ]);

        try {
            $laporan = new Laporan();
            $laporan->nama_laporan = $request->nama_laporan;
            $laporan->kecamatan = $request->kecamatan;
            $laporan->tanggal_seleksi = $request->tanggal_seleksi;
            $laporan->jumlah_kelompok_tani = $request->jumlah_kelompok_tani;
            $laporan->jenis_tani = $request->jenis_tani;
            $laporan->tahun = $request->tahun;
            $laporan->user_id = Auth::id(); // <<-- Ini penting untuk menyimpan user_id
            $laporan->save();

            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $tahun = Session::get('tahun');
        $jenis_tani = Session::get('jenis_tani');

        $laporans = Laporan::orderBy('created_at', 'desc')->where('tahun', $tahun)->where('jenis_tani', $jenis_tani)->get();
        return view('laporan.index', compact('laporans'));
    }
    public function show($id)
    {

        $subLaporans = SubLaporan::where('laporan_id', $id)->get();

        return view('laporan.detail', compact('id', 'subLaporans')); // Kirim ID ke tampilan (opsional)
    }

    public function cetakPdf($id)
    {
        $laporan = Laporan::findOrFail($id);
        $subLaporans = SubLaporan::where('laporan_id', $id)->get();

        $pdf = Pdf::loadView('laporan.cetak-pdf', [
            'laporan' => $laporan,
            'subLaporans' => $subLaporans
        ]);

        return $pdf->download('laporan-seleksi-' . $laporan->kecamatan . '.pdf');
    }


    public function destroy($id)
    {
        try {
            $laporan = Laporan::findOrFail($id);

            $kelompokTaniIds = $laporan->subLaporans()->pluck('kelompok_tani_id')->unique()->toArray();

            $laporan->subLaporans()->delete();

            $laporan->delete();

            $this->setStatusTani($kelompokTaniIds);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setStatusTani(array $kelompokTaniIds)
    {
        $affectedKelompokTanis = KelompokTani::whereIn('id', $kelompokTaniIds)->get();

        foreach ($affectedKelompokTanis as $kelompokTani) {
            $existsInOtherLaporan = SubLaporan::where('kelompok_tani_id', $kelompokTani->id)->exists();

            $kelompokTani->status = $existsInOtherLaporan ? 'terpilih' : 'tidak_terpilih';
            $kelompokTani->save();
        }
    }
}