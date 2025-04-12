<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani; // Import model
use App\Models\Seleksi;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\SubLaporan;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;





class DashboardController extends Controller
{
    public function index()
    {

        $tahun = Session::get('tahun');
        $kelompokTanis = KelompokTani::where('jenis_tani', Session::get('jenis_tani'))->where('tahun', $tahun)->get();

        $jumlahKelompokTani = $kelompokTanis->count();
        $jumlahKelompokTaniTerpilih = $kelompokTanis->where('status', 'terpilih')->count();
        $jumlahKelompokTaniTidakTerpilih = $kelompokTanis->where('status', 'tidak_terpilih')->count();

        $laporan = Laporan::where('jenis_tani', session('jenis_tani'))->where('tahun', $tahun)->latest()->first();
        $subLaporans = collect();

        if ($laporan) {
            $subLaporans = SubLaporan::where('laporan_id', $laporan->id)->get();
        }



        $jumlahTerpilih = KelompokTani::where('status', 'terpilih')->count();
        $jumlahTidakTerpilih = KelompokTani::where('status', 'tidak_terpilih')->count();


        // menghapus data 5 tahun lalu
        $tahun_batas = Carbon::now()->year - 5;
        Laporan::where('tahun', '<=', $tahun_batas)->delete();
        KelompokTani::where('tahun', '<=', $tahun_batas)->delete();
        Seleksi::where('tahun', '<=', $tahun_batas)->delete();

        return view('dashboard', compact('jumlahKelompokTani', 'jumlahTerpilih', 'jumlahTidakTerpilih', 'subLaporans', 'jumlahKelompokTaniTerpilih', 'jumlahKelompokTaniTidakTerpilih'));
    }


    public function setSessionJenisTani($jenis)
    {
        session(['jenis_tani' => $jenis]);
        return response()->json(['success' => true, 'message' => 'Session updated']);
    }

    public function setTahun(Request $request)
    {
        session(['tahun' => $request->tahun]);
        return redirect()->back(); // atau redirect ke route lain jika perlu
    }

}
