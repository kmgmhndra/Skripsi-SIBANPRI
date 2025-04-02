@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-start">
        <!-- Kiri: Judul & Deskripsi -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Hi, <strong>{{ Auth::user()->name }}</strong> Welcome back to BANPRI!</p>
        </div>

        <!-- Kanan: Dropdown Jenis Tani -->
        <div class="relative">
            <button id="dropdownButton"
                class="flex flex-col items-start bg-white p-4 rounded-lg shadow-md border border-gray-300 w-64">
                <div class="flex items-center">
                    <img src="{{ asset('images/periode.png') }}" alt="Calendar" class="w-6 h-6 mr-2">
                    <span class="text-gray-700 font-semibold" id="selectedJenisSeleksi">
                        @if(session('jenis_tani'))
                            {{ session('jenis_tani') }}
                        @else
                            Pilih Jenis Seleksi
                        @endif
                    </span>
                    <svg class="w-4 h-4 ml-auto text-gray-600 transition-transform duration-300" id="arrowIcon"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm text-gray-500 mt-1" id="currentJenisSeleksi">
                    @if(session('jenis_tani'))
                        Saat ini: {{ session('jenis_tani') }}
                    @else
                        Saat ini: Belum dipilih
                    @endif
                </p>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu"
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg hidden z-50">
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
                    onclick="selectJenisSeleksi('Padi')">Padi</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
                    onclick="selectJenisSeleksi('Palawija')">Palawija</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
                    onclick="selectJenisSeleksi('Pupuk')">Pupuk</a>
            </div>
        </div>
    </div>

    <!-- Kotak Statistik -->
    <div class="grid grid-cols-3 gap-4 mt-6">
        @php
            $stats = [
                ['img' => 'ikon_jumlah.png', 'value' => $jumlahKelompokTani, 'label' => 'Jumlah Usulan (CPCL)', 'color' => 'text-yellow-500'],
                ['img' => 'ikon_terpilih.png', 'value' => $jumlahKelompokTaniTerpilih, 'label' => 'Total Kelompok Terpilih', 'color' => 'text-green-500'],
                ['img' => 'ikon_eliminasi.png', 'value' => $jumlahKelompokTaniTidakTerpilih, 'label' => 'Total Kelompok Tereliminasi', 'color' => 'text-red-500']
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
                    <th class="p-3 text-center">Skor WPM</th>
                    <th class="p-3 text-center">Ranking</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subLaporans as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $item->nama_kelompok_tani }}</td>
                        <td class="p-3">{{ $item->nama_desa }}</td>
                        <td class="p-3 text-center">{{ $item->nilai_wpm }}</td>
                        <td class="p-3 text-center">{{ $item->peringkat }}</td>
                        <td class="p-3 text-center">
                            <a href="/detail/{{ $item->laporan_id }}">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded-md">Detail</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {


            const dropdownButton = document.getElementById("dropdownButton");
            const dropdownMenu = document.getElementById("dropdownMenu");
            const arrowIcon = document.getElementById("arrowIcon");



            if (!dropdownButton || !dropdownMenu || !arrowIcon) {

                return;
            }

            // Toggle dropdown menu
            dropdownButton.addEventListener("click", function (event) {

                event.stopPropagation();
                dropdownMenu.classList.toggle("hidden");
                arrowIcon.classList.toggle("rotate-180");
            });

            // Close dropdown when clicking outside
            document.addEventListener("click", function (event) {
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {

                    dropdownMenu.classList.add("hidden");
                    arrowIcon.classList.remove("rotate-180");
                }
            });
        });
        function selectJenisSeleksi(jenis) {
            // Update the UI immediately
            document.getElementById('selectedJenisSeleksi').textContent = jenis;
            document.getElementById('currentJenisSeleksi').textContent = 'Saat ini: ' + jenis;

            // Close the dropdown
            document.getElementById('dropdownMenu').classList.add('hidden');
            document.getElementById('arrowIcon').classList.remove('rotate-180');

            // Send AJAX request to set session
            fetch(`/setSessionJenisTani/${jenis}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {

                    // Reload the page to reflect changes
                    window.location.reload();
                })
                .catch(error => {

                    // Revert UI changes if there was an error
                    document.getElementById('selectedJenisSeleksi').textContent =
                        document.getElementById('selectedJenisSeleksi').dataset.previous || 'Pilih Jenis Seleksi';
                    document.getElementById('currentJenisSeleksi').textContent =
                        'Saat ini: ' + (document.getElementById('selectedJenisSeleksi').dataset.previous || 'Belum dipilih');
                });
        }
    </script>
@endsection