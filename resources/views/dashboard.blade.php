@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="flex justify-between items-start">
    <!-- Kiri: Judul & Deskripsi -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">Hi, Mahendra. Welcome back to BANPRI!</p>
    </div>

    <!-- Kanan: Dropdown Periode -->
    <div class="relative">
        <button id="dropdownButton"
            class="flex flex-col items-start bg-white p-4 rounded-lg shadow-md border border-gray-300 w-64">
            <div class="flex items-center">
                <img src="{{ asset('images/periode.png') }}" alt="Calendar" class="w-6 h-6 mr-2">
                <span class="text-gray-700 font-semibold" id="selectedPeriod">Pilih Jenis Seleksi</span>
                <svg class="w-4 h-4 ml-auto text-gray-600 transition-transform duration-300" id="arrowIcon"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-sm text-gray-500 mt-1" id="currentPeriodLabel">Saat ini: Belum dipilih</p>
        </button>

        <!-- Dropdown Menu -->
        <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100" onclick="selectPeriod('Padi')">Padi</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100" onclick="selectPeriod('Palawija')">Palawija</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100" onclick="selectPeriod('Pupuk')">Pupuk</a>
        </div>
    </div>
</div>

<!-- Kotak Statistik -->
<div class="grid grid-cols-3 gap-4 mt-6">
    @php
        $stats = [
            ['img' => 'ikon_jumlah.png', 'value' => 175, 'label' => 'Jumlah Usulan (CPCL)', 'color' => 'text-yellow-500'],
            ['img' => 'ikon_terpilih.png', 'value' => 135, 'label' => 'Total Kelompok Terpilih', 'color' => 'text-green-500'],
            ['img' => 'ikon_eliminasi.png', 'value' => 40, 'label' => 'Total Kelompok Tereliminasi', 'color' => 'text-red-500']
        ];
    @endphp

    @foreach($stats as $stat)
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
            <div class="p-3 rounded-full">
                <img src="{{ asset('images/' . $stat['img']) }}" alt="{{ $stat['label'] }}" class="w-16">
            </div>
            <div>
                <h2 class="text-4xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</h2>
                <p class="text-gray-700">{{ $stat['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

<!-- Tabel Data -->
<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Data Terbaru Hasil Seleksi</h2>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 text-left">Nama Kelompok</th>
                <th class="p-3 text-left">Kecamatan</th>
                <th class="p-3 text-left">Skor Prioritas</th>
                <th class="p-3 text-left">Ranking</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t">
                <td class="p-3">Lorem Ipsum Dolor</td>
                <td class="p-3">Busungbiu</td>
                <td class="p-3">9876356</td>
                <td class="p-3">1</td>
                <td class="p-3">
                    <button class="bg-purple-600 text-white px-3 py-1 rounded-md">Detail</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Script untuk Dropdown -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const dropdownButton = document.getElementById("dropdownButton");
    const dropdownMenu = document.getElementById("dropdownMenu");
    const arrowIcon = document.getElementById("arrowIcon");

    dropdownButton.addEventListener("click", function (event) {
        event.stopPropagation();
        dropdownMenu.classList.toggle("hidden");
        arrowIcon.classList.toggle("rotate-180");
    });

    document.addEventListener("click", function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add("hidden");
            arrowIcon.classList.remove("rotate-180");
        }
    });
});

function selectPeriod(period) {
    document.getElementById("selectedPeriod").textContent = "Pilih Periode";
    document.getElementById("currentPeriodLabel").textContent = "Saat ini: " + period;
    document.getElementById("dropdownMenu").classList.add("hidden");
    document.getElementById("arrowIcon").classList.remove("rotate-180");
}
</script>

@endsection
