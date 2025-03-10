<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani; // Import model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $jumlahKelompokTani = KelompokTani::count();
        $jumlahTerpilih = KelompokTani::where('status', 'terpilih')->count();
        $jumlahTidakTerpilih = KelompokTani::where('status', 'tidak_terpilih')->count();

        return view('dashboard', compact('jumlahKelompokTani', 'jumlahTerpilih', 'jumlahTidakTerpilih'));
    }
}
