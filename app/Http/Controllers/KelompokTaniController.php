<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kecamatan;
use App\Models\Kriteria;
use App\Models\KriteriaValue;

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

        $kriterias = Kriteria::all();
 // Ambil nilai kriteria untuk kelompok tani yang ada
 $kelompokTaniIds = $kelompokTani->pluck('id');
 $kriteriaValues = KriteriaValue::whereIn('kelompok_tani_id', $kelompokTaniIds)->get();

 // Bentuk array asosiatif: ['kelompok_tani_id' => ['kriteria_id' => nilai]]
 $kriteriaValuesArray = [];
 foreach ($kriteriaValues as $value) {
     $kriteriaValuesArray[$value->kelompok_tani_id][$value->kriteria_id] = $value->value;
 }

        return view('kelompok-tani.index', compact('kecamatan', 'kelompokTani', 'selectedKecamatan', 'kriterias', 'kriteriaValuesArray'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            // 'simluhtan' => 'required|in:1,5',
            // 'terpoligon' => 'required|in:1,5',
            // 'bantuan_sebelumnya' => 'required|in:1,5', // Ganti dari riwayat
            // 'dpi' => 'required|numeric|min:0',
            // 'provitas' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        // Set default status menjadi 'tidak_terpilih'
        $request->merge(['status' => 'tidak_terpilih']);

        // Simpan data kelompok tani
        $kelompok_tani = KelompokTani::create($request->all());

        //
        $data = $request->input('kriteria_value'); // Mengambil semua input dari form
        // $kelompokTaniId = $request->input('kelompok_tani_id'); // Ambil ID kelompok tani dari form
    
        foreach ($data as $kriteriaId => $nilai) {
            KriteriaValue::updateOrCreate(
                [
                    'kriteria_id' => $kriteriaId,
                    'kelompok_tani_id' => $kelompok_tani->id
                ],
                ['value' => $nilai]
            );
        }
        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $kelompokTani = KelompokTani::findOrFail($id);
        $kecamatan = Kecamatan::all();
        
        // Ambil nilai kriteria untuk kelompok tani ini
        $kriteriaValues = KriteriaValue::where('kelompok_tani_id', $id)
            ->select('kriteria_id', 'value', 'kelompok_tani_id')
            ->get();

            // dd($kriteriaValues);
    
        return response()->json(compact('kelompokTani', 'kecamatan', 'kriteriaValues'));
    }
    


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'status' => 'nullable|in:aktif,nonaktif',
            'kriteria_value_edit' => 'nullable|array', // Pastikan ini berupa array
            'kriteria_value_edit.*' => 'nullable|numeric', // Pastikan isinya angka
        ]);
    
        // Jika status kosong, set default ke 'tidak_terpilih'
        $status = $request->status ?: 'tidak_terpilih';
    
        // Cari data kelompok tani dan update
        $kelompokTani = KelompokTani::findOrFail($id);
        $kelompokTani->update([
            'nama' => $request->nama,
            'desa' => $request->desa,
            'ketua' => $request->ketua,
            'kecamatan_id' => $request->kecamatan_id,
            'status' => $status,
        ]);
    
        // Update atau Insert kriteria_value
        if ($request->has('kriteria_value_edit')) {
            foreach ($request->kriteria_value_edit as $kriteria_id => $value) {
                KriteriaValue::updateOrCreate(
                    [
                        'kelompok_tani_id' => $id,
                        'kriteria_id' => $kriteria_id,
                    ],
                    ['value' => $value]
                );
            }
        }
    
        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani dan Kriteria Value berhasil diperbarui!');
    }
    

    public function destroy($id)
    {
        KelompokTani::findOrFail($id)->delete();

        KriteriaValue::where('kelompok_tani_id', $id)->delete();

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