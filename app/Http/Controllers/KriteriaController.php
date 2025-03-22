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
        $request->validate([
            'nama' => 'required|string',
            'urutan' => 'required|integer|min:1',
            'jenis' => 'required|in:benefit,cost'
        ]);

        DB::transaction(function () use ($request) {
            // Geser kriteria ke bawah, mulai dari yang terbesar dulu
            DB::table('kriteria')
                ->where('urutan', '>=', $request->urutan)
                ->orderBy('urutan', 'desc')
                ->update(['urutan' => DB::raw('urutan + 1')]);

            // Tambahkan kriteria baru
            Kriteria::create([
                'nama' => $request->nama,
                'urutan' => $request->urutan,
                'jenis' => $request->jenis
            ]);

            // Hitung ulang bobot ROC
            $this->hitungUlangBobotROC();
        });

        return response()->json(['success' => true, 'message' => 'Kriteria berhasil ditambahkan']);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'urutan' => 'required|integer|min:1',
            'jenis' => 'required|in:benefit,cost'
        ]);

        DB::transaction(function () use ($request, $id) {
            $kriteria = Kriteria::findOrFail($id);
            $urutanLama = $kriteria->urutan;
            $urutanBaru = $request->urutan;

            if ($urutanLama != $urutanBaru) {
                if ($urutanLama < $urutanBaru) {
                    DB::table('kriteria')
                        ->whereBetween('urutan', [$urutanLama + 1, $urutanBaru])
                        ->orderBy('urutan', 'asc')
                        ->update(['urutan' => DB::raw('urutan - 1')]);
                } else {
                    DB::table('kriteria')
                        ->whereBetween('urutan', [$urutanBaru, $urutanLama - 1])
                        ->orderBy('urutan', 'desc')
                        ->update(['urutan' => DB::raw('urutan + 1')]);
                }
            }

            // Update kriteria
            $kriteria->update([
                'nama' => $request->nama,
                'urutan' => $urutanBaru,
                'jenis' => $request->jenis
            ]);

            // Hitung ulang bobot ROC
            $this->hitungUlangBobotROC();
        });

        return response()->json(['success' => true, 'message' => 'Kriteria berhasil diperbarui']);
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $kriteria = Kriteria::findOrFail($id);
            $urutanLama = $kriteria->urutan;

            // Hapus kriteria
            $kriteria->delete();

            // Perbaiki urutan setelah penghapusan
            DB::table('kriteria')
                ->where('urutan', '>', $urutanLama)
                ->decrement('urutan');

            // Hitung ulang bobot ROC
            $this->hitungUlangBobotROC();
        });

        return response()->json(['success' => true, 'message' => 'Kriteria berhasil dihapus']);
    }

    private function hitungUlangBobotROC()
    {
        $kriteria = Kriteria::orderBy('urutan', 'asc')->get();
        $jumlahKriteria = $kriteria->count();

        if ($jumlahKriteria == 0) {
            return;
        }

        foreach ($kriteria as $index => $item) {
            $rank = $index + 1;
            $bobot = 0;

            for ($j = $rank; $j <= $jumlahKriteria; $j++) {
                $bobot += 1 / $j;
            }

            $bobotROC = $bobot / $jumlahKriteria;

            // Update bobot di database
            $item->update(['bobot' => $bobotROC]);
        }
    }
}
