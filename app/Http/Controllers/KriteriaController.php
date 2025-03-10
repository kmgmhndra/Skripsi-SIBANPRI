<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::orderBy('urutan')->get();
        return view('kriteria.index', compact('kriteria'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|unique:kriteria,nama',
                'urutan' => 'required|integer|min:1'
            ]);

            $urutanBaru = $request->urutan;
            $jumlahKriteria = Kriteria::count();

            // Jika urutan lebih dari jumlah kriteria, tambahkan di akhir
            if ($urutanBaru > $jumlahKriteria + 1) {
                $urutanBaru = $jumlahKriteria + 1;
            }

            // **1. Geser urutan dari belakang untuk mencegah duplikasi**
            Kriteria::where('urutan', '>=', $urutanBaru)
                ->orderBy('urutan', 'desc') // Geser dari urutan terbesar
                ->update(['urutan' => \DB::raw('urutan + 1')]);

            // **2. Tambahkan kriteria baru dengan urutan yang sudah disiapkan**
            $kriteria = Kriteria::create([
                'nama' => $request->nama,
                'urutan' => $urutanBaru,
                'bobot' => 0
            ]);

            // **3. Hitung ulang bobot ROC**
            Kriteria::hitungBobotROC();

            return response()->json(['success' => true, 'message' => 'Kriteria berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kriteria,nama,' . $id,
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->nama = $request->nama;
        $kriteria->save();

        return response()->json([
            'success' => true,
            'message' => 'Kriteria berhasil diperbarui!',
            'data' => $kriteria
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $kriteria = Kriteria::findOrFail($id);
            $urutanDihapus = $kriteria->urutan;
            $kriteria->delete();

            Kriteria::where('urutan', '>', $urutanDihapus)->decrement('urutan');
            Kriteria::hitungBobotROC();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kriteria berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kriteria: ' . $e->getMessage()
            ], 500);
        }
    }
}