<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokTani;
use App\Models\Kecamatan;
use App\Models\Kriteria;
use App\Models\KriteriaValue;
use Illuminate\Support\Facades\Session;
use App\Imports\KelompokTaniImport;
use Maatwebsite\Excel\Facades\Excel;


class KelompokTaniController extends Controller
{
    public function index(Request $request)
    {
        // Get all kecamatan
        $kecamatan = Kecamatan::all();
        $jenisTani = Session::get('jenis_tani');


        $kelompokTani = KelompokTani::where('jenis_tani', $jenisTani)->get();

        $kecamatanId = $kelompokTani->isNotEmpty() ? $kelompokTani->first()->kecamatan_id : null;

        $kriterias = Kriteria::all();

        $kelompokTaniIds = $kelompokTani->pluck('id');
        $kriteriaValues = KriteriaValue::whereIn('kelompok_tani_id', $kelompokTaniIds)->get();

        $kriteriaValuesArray = [];
        foreach ($kriteriaValues as $value) {
            $kriteriaValuesArray[$value->kelompok_tani_id][$value->kriteria_id] = $value->value;
        }

        return view('kelompok-tani.index', compact(
            'kecamatan',
            'kelompokTani',
            'kecamatanId',
            'kriterias',
            'kriteriaValuesArray',
            'jenisTani'
        ));
    }
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);


        $jenisTani = Session::get('jenis_tani');

        // Gabungkan data yang divalidasi dengan data tambahan
        $data = array_merge($validated, [
            'jenis_tani' => $jenisTani,
            'status' => 'tidak_terpilih' // Set default status
        ]);

        // Simpan data kelompok tani
        $kelompok_tani = KelompokTani::create($data);

        // Simpan kriteria nilai
        if ($request->has('kriteria_value')) {
            foreach ($request->input('kriteria_value') as $kriteriaId => $nilai) {
                KriteriaValue::updateOrCreate(
                    [
                        'kriteria_id' => $kriteriaId,
                        'kelompok_tani_id' => $kelompok_tani->id
                    ],
                    ['value' => $nilai]
                );
            }
        }

        return redirect()->route('kelompok-tani.index')
            ->with('success', 'Kelompok Tani berhasil ditambahkan!');
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
            'kriteria_value_edit' => 'nullable|array',
            'kriteria_value_edit.*' => 'nullable|numeric',
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

        // HasilSeleksi::where('kelompok_tani_id', $id)->delete();

        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil dihapus!');
    }
    

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        $kecamatan_id = $request->kecamatan_id;
        $jenis_tani = Session::get('jenis_tani');

        try {
            Excel::import(new KelompokTaniImport($kecamatan_id, $jenis_tani), $request->file('file'));
            return back()->with('success', 'Data berhasil diimport');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}