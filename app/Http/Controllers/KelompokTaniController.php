<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kecamatan;

class KelompokTaniController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kecamatan
        $kecamatan = Kecamatan::all();

        // Jika ada kecamatan yang dipilih, filter data kelompok tani berdasarkan kecamatan
        $selectedKecamatan = $request->input('kecamatan_id');
        $kelompokTani = KelompokTani::when($selectedKecamatan, function ($query) use ($selectedKecamatan) {
            return $query->where('kecamatan_id', $selectedKecamatan);
        })->with('kecamatan')->get();

        return view('kelompok-tani.index', compact('kecamatan', 'kelompokTani', 'selectedKecamatan'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'simluhtan' => 'required|in:1,5',
            'terpoligon' => 'required|in:1,5',
            'bantuan_sebelumnya' => 'required|in:1,5', // Ganti dari riwayat
            'dpi' => 'required|numeric|min:0',
            'provitas' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        // Set default status menjadi 'tidak_terpilih'
        $request->merge(['status' => 'tidak_terpilih']);

        // Simpan data kelompok tani
        KelompokTani::create($request->all());

        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelompokTani = KelompokTani::findOrFail($id);
        $kecamatan = Kecamatan::all();

        return response()->json(compact('kelompokTani', 'kecamatan'));
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'simluhtan' => 'required|in:1,5',
            'terpoligon' => 'required|in:1,5',
            'bantuan_sebelumnya' => 'required|in:1,5',
            'dpi' => 'required|numeric|min:0',
            'provitas' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif', // Status jadi opsional
        ]);

        // Jika status kosong, set status ke 'tidak_terpilih' sebagai default
        $status = $request->status ?: 'tidak_terpilih';

        // Cari data kelompok tani dan update
        $kelompokTani = KelompokTani::findOrFail($id);
        $kelompokTani->update([
            'nama' => $request->nama,
            'desa' => $request->desa,
            'ketua' => $request->ketua,
            'kecamatan_id' => $request->kecamatan_id,
            'simluhtan' => $request->simluhtan,
            'terpoligon' => $request->terpoligon,
            'bantuan_sebelumnya' => $request->bantuan_sebelumnya,
            'dpi' => intval($request->dpi), // Menghilangkan desimal
            'provitas' => intval($request->provitas), // Menghilangkan desimal
            'status' => $status, // Pastikan status ada
        ]);

        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil diperbarui!');
    }

    public function destroy($id)
    {
        KelompokTani::findOrFail($id)->delete();

        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new KelompokTaniImport, $request->file('file'));

        return redirect()->route('kelompok-tani.index')->with('success', 'Data Kelompok Tani berhasil diimpor!');
    }
}
