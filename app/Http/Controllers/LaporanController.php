<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\SubLaporan;
use App\Models\KelompokTani;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::orderBy('created_at', 'desc')->get();
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
