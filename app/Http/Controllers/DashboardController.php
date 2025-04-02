<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani; // Import model
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\SubLaporan;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function index()
    {

        $kelompokTanis = KelompokTani::where('jenis_tani', Session::get('jenis_tani'))->get();

        $jumlahKelompokTani = $kelompokTanis->count();
        $jumlahKelompokTaniTerpilih = $kelompokTanis->where('status', 'terpilih')->count();
        $jumlahKelompokTaniTidakTerpilih = $kelompokTanis->where('status', 'tidak_terpilih')->count();

        $laporan = Laporan::where('jenis_tani', session('jenis_tani'))->latest()->first();
        $subLaporans = collect();

        if ($laporan) {
            $subLaporans = SubLaporan::where('laporan_id', $laporan->id)->get();
        }

        $jumlahTerpilih = KelompokTani::where('status', 'terpilih')->count();
        $jumlahTidakTerpilih = KelompokTani::where('status', 'tidak_terpilih')->count();

        return view('dashboard', compact('jumlahKelompokTani', 'jumlahTerpilih', 'jumlahTidakTerpilih', 'subLaporans', 'jumlahKelompokTaniTerpilih', 'jumlahKelompokTaniTidakTerpilih'));
    }


    public function setSessionJenisTani($jenis)
    {
        session(['jenis_tani' => $jenis]);
        return response()->json(['success' => true, 'message' => 'Session updated']);
    }
}
