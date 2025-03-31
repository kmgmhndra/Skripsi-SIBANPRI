@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <!-- Judul & Informasi Awal -->
        <div class="mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Detail Hasil Seleksi - Kecamatan Contoh</h1>
            <p class="text-gray-600 mt-1">
                Berikut adalah informasi lengkap mengenai hasil seleksi yang dilakukan pada :
            </p>
            <p class="text-gray-600">Tanggal Seleksi: <span class="font-semibold">{{ now()->format('d M Y') }}</span></p>
            <p class="text-gray-600">Jumlah Kelompok Tani Terpilih: <span class="font-semibold">5</span></p>
        </div>

        <!-- Tabel Kelompok Tani -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700">Daftar Kelompok Tani</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg mt-2">
                    <thead class="bg-gray-200">
                        <tr class="text-left">
                            <th class="py-2 px-4 border">No</th>
                            <th class="py-2 px-4 border">Nama Kelompok Tani</th>
                            <th class="py-2 px-4 border">Nama Ketua</th>
                            <th class="py-2 px-4 border">Nama Desa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(range(1,5) as $index) {{-- Data dummy --}}
                        <tr>
                            <td class="py-2 px-4 border">{{ $index }}</td>
                            <td class="py-2 px-4 border">Kelompok Tani {{ $index }}</td>
                            <td class="py-2 px-4 border">Ketua {{ $index }}</td>
                            <td class="py-2 px-4 border">Desa {{ $index }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex space-x-2">
            <a href="{{ route('laporan.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                Kembali
            </a>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Cetak Laporan
            </button>
        </div>
    </div>
</div>
@endsection
