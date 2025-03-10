<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\Kecamatan;
use App\Models\RiwayatSeleksi;

class SeleksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kecamatan untuk dropdown
        $kecamatans = Kecamatan::all(); 
        
        // Ambil kecamatan yang dipilih dari request atau default ke kecamatan pertama
        $kecamatanId = $request->kecamatan_id ?? ($kecamatans->isNotEmpty() ? $kecamatans->first()->id : null);
        
        // Ambil hasil seleksi berdasarkan kecamatan yang dipilih
        $hasilSeleksi = session("hasilSeleksi_$kecamatanId", []);

        return view('seleksi.index', compact('hasilSeleksi', 'kecamatans', 'kecamatanId'));
    }
}
