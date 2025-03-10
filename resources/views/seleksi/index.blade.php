@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Hasil Seleksi</h2>

    <!-- Dropdown Kecamatan -->
    <form method="GET" action="{{ route('hasil-seleksi.index') }}" class="mb-4">
        <label for="kecamatan_id" class="font-semibold">Pilih Kecamatan:</label>
        <select name="kecamatan_id" id="kecamatan_id" class="border p-2 rounded">
            @foreach($kecamatans as $kecamatan)
                <option value="{{ $kecamatan->id }}" 
                    {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                    {{ $kecamatan->nama }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Tampilkan</button>
    </form>

    <!-- Tabel Hasil Seleksi -->
    <div class="bg-white rounded-lg shadow-md p-4">
        @if (!empty($hasilSeleksi))
            <form method="POST" action="{{ route('hasil-seleksi.simpan') }}">
                @csrf
                <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">

                <table class="w-full border-collapse border">
                    <thead class="bg-gray-200 text-gray-700 font-bold uppercase text-sm">
                        <tr>
                            <th class="border px-4 py-2 text-left">No</th>
                            <th class="border px-4 py-2 text-left">Nama Kelompok</th>
                            <th class="border px-4 py-2 text-left">Nama Ketua</th>
                            <th class="border px-4 py-2 text-left">Desa</th>
                            <th class="border px-4 py-2 text-center">Nilai WPM</th>
                            <th class="border px-4 py-2 text-center">Ranking</th>
                            <th class="border px-4 py-2 text-center">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilSeleksi as $index => $tani)
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $tani['nama'] }}</td>
                            <td class="border px-4 py-2">{{ $tani['nama_ketua'] }}</td>
                            <td class="border px-4 py-2">{{ $tani['desa'] }}</td>
                            <td class="border px-4 py-2 text-center">{{ number_format($tani['nilai'], 4) }}</td>
                            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2 text-center">
                                <input type="checkbox" name="kelompok_tani_id[]" value="{{ $tani['id'] }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Simpan Seleksi</button>
            </form>
        @else
            <p class="text-gray-600 text-center">Belum ada hasil seleksi untuk kecamatan ini.</p>
        @endif
    </div>
</div>
@endsection
