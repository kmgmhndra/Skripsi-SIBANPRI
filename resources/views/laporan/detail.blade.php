@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">

            <!-- Informasi Awal -->
            <div class="mb-4">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Detail Hasil Seleksi - {{ $subLaporans->first()->laporan->kecamatan ?? 'Kecamatan' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    Berikut adalah informasi lengkap mengenai hasil seleksi yang dilakukan pada:
                </p>
                <p class="text-gray-600">Tanggal Seleksi:
                    <span class="font-semibold">
                        @if($subLaporans->isNotEmpty())
                            {{ \Carbon\Carbon::parse($subLaporans->first()->laporan->tanggal_seleksi)->format('d M Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </p>
                <p class="text-gray-600">Jumlah Kelompok Tani Terpilih:
                    <span class="font-semibold">{{ $subLaporans->count() }}</span>
                </p>
                <p class="text-gray-600">Komoditas:
                    <span class="font-semibold">{{ $subLaporans->first()->laporan->jenis_tani }}</span>
                </p>
                <p class="text-gray-600">Tahun Laporan:
                    <span class="font-semibold">{{ $subLaporans->first()->laporan->tahun }}</span>
                </p>
            </div>

            <!-- Tabel Kelompok Tani -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Kelompok Tani</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg mt-2 text-sm md:text-base">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-3 border text-center">No</th>
                                <th class="py-2 px-3 border text-left">Nama Kelompok Tani</th>
                                <th class="py-2 px-3 border text-left">Nama Ketua</th>
                                <th class="py-2 px-3 border text-left">Nama Desa</th>
                                <th class="py-2 px-3 border text-center">Nilai WPM</th>
                                <th class="py-2 px-3 border text-center">Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subLaporans as $index => $subLaporan)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-3 border text-center">{{ $index + 1 }}</td>
                                    <td class="py-2 px-3 border">{{ $subLaporan->nama_kelompok_tani }}</td>
                                    <td class="py-2 px-3 border">{{ $subLaporan->nama_ketua }}</td>
                                    <td class="py-2 px-3 border">{{ $subLaporan->nama_desa }}</td>
                                    <td class="py-2 px-3 border text-center">{{ $subLaporan->nilai_wpm }}</td>
                                    <td class="py-2 px-3 border text-center">{{ $subLaporan->peringkat }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-2 px-3 border text-center text-gray-500">Tidak ada data kelompok
                                        tani</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex flex-col md:flex-row gap-2">
                <a href="{{ route('laporan.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg text-center hover:bg-gray-600 transition">
                    Kembali
                </a>
                @if($subLaporans->isNotEmpty())
                    <a href="{{ route('laporan.download', $subLaporans->first()->laporan->id) }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-600 transition">
                        Cetak Laporan
                    </a>
                @endif
            </div>

        </div>
    </div>
@endsection