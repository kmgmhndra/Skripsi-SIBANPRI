<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function show($id)
    {
        return view('laporan.detail', compact('id')); // Kirim ID ke tampilan (opsional)
    }
}
