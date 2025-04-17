@extends('layouts.app')

@section('content')


<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
    <!-- Kiri: Judul & Deskripsi -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Hasil Seleksi</h1>
        <p class="text-gray-600 mt-1">Berikut adalah hasil seleksi WPM</p>
    </div>

    <!-- Dropdown Kecamatan -->
    <div class="relative w-full md:w-auto z-20">
        <button id="dropdownKecamatanButton"
            class="flex flex-col items-start bg-white p-4 rounded-lg shadow-md border border-gray-300 w-full md:w-64">
            <div class="flex items-center w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt"
                    viewBox="0 0 16 16">
                    <path
                        d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
                <span class="text-gray-700 font-semibold ml-2" id="selectedKecamatan">
                    @if(request('kecamatan_id'))
                    {{ $kecamatan->firstWhere('id', request('kecamatan_id'))->nama ?? 'Pilih Kecamatan' }}
                    @else
                    {{ $kecamatan->first()->nama ?? 'Pilih Kecamatan' }}
                    @endif
                </span>
                <svg class="w-4 h-4 ml-auto text-gray-600 transition-transform duration-300" id="arrowKecamatanIcon"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-sm text-gray-500 mt-1" id="currentKecamatanLabel">
                @if(request('kecamatan_id'))
                {{ $kecamatan->firstWhere('id', request('kecamatan_id'))->nama ?? 'Pilih Kecamatan' }}
                @else
                {{ $kecamatan->first()->nama ?? 'Pilih Kecamatan' }}
                @endif
            </p>
        </button>

        <!-- Dropdown Menu Kecamatan -->
        <div id="dropdownKecamatanMenu"
            class="absolute right-0 mt-2 w-full md:w-64 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
            @foreach($kecamatan as $item)
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
                onclick="selectKecamatan('{{ $item->id }}', '{{ $item->nama }}')">
                {{ $item->nama }}
            </a>
            @endforeach
        </div>
    </div>
</div>

@auth
@if(auth()->user()->role === 'admin')
<div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
    <label for="rangeInput" class="text-gray-700 font-medium">Checklist otomatis (misal: 1-10):</label>

    <div class="flex items-center gap-2">
        <input type="text" id="rangeInput"
            class="border border-gray-300 rounded-lg p-2 w-36 focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Contoh: 1-10" oninput="toggleButton()">

        <button type="button" id="selectButton" onclick="checkRange()"
            class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
            disabled>
            Pilih
        </button>
    </div>
</div>

<script>
function toggleButton() {
    const input = document.getElementById('rangeInput');
    const button = document.getElementById('selectButton');
    button.disabled = input.value.trim() === '';
}
</script>
@endif
@endauth




<!-- Tabel Hasil Seleksi -->
<!-- Versi Desktop -->
<div class="bg-white rounded-lg shadow-md p-4 hidden md:block">
    @if (!empty($hasilSeleksi) && count($hasilSeleksi) > 0)
    <form method="POST" action="{{ route('hasil-seleksi.simpan') }}">
        @csrf
        <input type="hidden" name="kecamatan_id" value="{{ $kecamatanId }}">

        <table class="w-full border-collapse border">
            <thead class="bg-gray-200 text-gray-700 font-bold uppercase text-sm">
                <tr>
                    <th class="border px-4 py-2 text-left">No</th>
                    <th class="border px-4 py-2 text-left">Nama Kelompok</th>
                    <th class="border px-4 py-2 text-left">Nama Ketua</th>
                    <th class="border px-4 py-2 text-left">Desa</th>
                    <th class="border px-4 py-2 text-center">Nilai WPM</th>
                    <th class="border px-4 py-2 text-center">Ranking</th>
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <th class="border px-4 py-2 text-center">Pilih</th>
                    @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach($hasilSeleksi as $index => $tani)
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $tani['nama_kelompok_tani'] }}</td>
                    <td class="border px-4 py-2">{{ $tani['ketua'] }}</td>
                    <td class="border px-4 py-2">{{ $tani['desa'] }}</td>
                    <td class="border px-4 py-2 text-center">{{ number_format($tani['nilai_wpm'], 4) }}</td>
                    <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <td class="border px-4 py-2 text-center">
                        <input type="checkbox" class="checkbox-kelompok" data-index="{{ $index + 1 }}"
                            name="kelompok_tani_id[]" value="{{ $tani['kelompok_tani_id'] }}">
                    </td>
                    @endif
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>

        @auth
        @if(auth()->user()->role === 'admin')
        <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan
            Seleksi</button>
        @endif
        @endauth
    </form>
    @else
    <p class="text-gray-600 text-center">Belum ada hasil seleksi untuk kecamatan ini.</p>
    @endif
</div>

<!-- Versi Mobile Tabel -->
<div class="bg-white rounded-lg shadow-md p-4 block md:hidden">
    @if (!empty($hasilSeleksi) && count($hasilSeleksi) > 0)
    <form method="POST" action="{{ route('hasil-seleksi.simpan') }}">
        @csrf
        <input type="hidden" name="kecamatan_id" value="{{ $kecamatanId }}">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-2 py-2">#</th>
                        <th class="px-2 py-2">Kelompok Tani</th>
                        <th class="px-2 py-2">Ketua</th>
                        <th class="px-2 py-2">Desa</th>
                        <th class="px-2 py-2">Nilai</th>
                        @auth
                        @if(auth()->user()->role === 'admin')
                        <th class="px-2 py-2">Pilih</th>
                        @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasilSeleksi as $index => $tani)
                    <tr class="bg-white border-b">
                        <td class="px-2 py-2">{{ $index + 1 }}</td>
                        <td class="px-2 py-2">{{ $tani['nama_kelompok_tani'] }}</td>
                        <td class="px-2 py-2">{{ $tani['ketua'] }}</td>
                        <td class="px-2 py-2">{{ $tani['desa'] }}</td>
                        <td class="px-2 py-2">{{ number_format($tani['nilai_wpm'], 4) }}</td>
                        @auth
                        @if(auth()->user()->role === 'admin')
                        <td class="px-2 py-2">
                            <input type="checkbox" name="kelompok_tani_id[]" value="{{ $tani['kelompok_tani_id'] }}">
                        </td>
                        @endif
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @auth
        @if(auth()->user()->role === 'admin')
        <button type="submit" class="w-full mt-4 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">
            Simpan Seleksi
        </button>
        @endif
        @endauth
    </form>
    @else
    <p class="text-gray-600 text-center">Belum ada hasil seleksi untuk kecamatan ini.</p>
    @endif
</div>



<!-- Script untuk dropdown dan reload -->
<script>
document.getElementById('dropdownKecamatanButton').addEventListener('click', function() {
    document.getElementById('dropdownKecamatanMenu').classList.toggle('hidden');
});

function selectKecamatan(id, name) {
    document.getElementById('selectedKecamatan').textContent = name;
    document.getElementById('dropdownKecamatanMenu').classList.add('hidden');

    // Reload halaman dengan parameter kecamatan_id yang dipilih
    const url = new URL(window.location.href);
    url.searchParams.set('kecamatan_id', id);
    window.location.href = url.toString();
}

// Klik di luar dropdown untuk menutupnya
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownKecamatanMenu');
    const button = document.getElementById('dropdownKecamatanButton');

    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

<script>
function checkRange() {
    const rangeValue = document.getElementById('rangeInput').value;
    const checkboxes = document.querySelectorAll('.checkbox-kelompok');

    // Reset semua dulu
    checkboxes.forEach(cb => cb.checked = false);

    // Cek validitas input
    const parts = rangeValue.split('-');
    if (parts.length !== 2) return alert('Masukkan format angka seperti 1-10');

    let start = parseInt(parts[0]);
    let end = parseInt(parts[1]);

    if (isNaN(start) || isNaN(end) || start > end || start < 1) {
        return alert('Range tidak valid');
    }

    // Checklist berdasarkan data-index
    checkboxes.forEach(cb => {
        const index = parseInt(cb.dataset.index);
        if (index >= start && index <= end) {
            cb.checked = true;
        }
    });
}
</script>

@endsection