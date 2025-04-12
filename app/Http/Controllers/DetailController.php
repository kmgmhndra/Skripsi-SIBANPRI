<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\SubLaporan;

class DetailController extends Controller
{
    public function show($id)
    {
        $subLaporans = SubLaporan::where('laporan_id', $id)->get();

        return view('laporan.detail', compact('id', 'subLaporans')); // Kirim ID ke tampilan
    }
}
