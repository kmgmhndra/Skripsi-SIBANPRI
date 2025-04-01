@extends('layouts.app')

@section('content')

    <div class="flex justify-between items-start">
        <!-- Kiri: Judul & Deskripsi -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Hasil Seleksi</h1>
            <p class="text-gray-600 mt-1">Hi, selalu update data ini ya!</p>
        </div>

        <!-- Dropdown Kecamatan -->
        <div class="relative mb-4">
            <button id="dropdownKecamatanButton"
                class="flex flex-col items-start bg-white p-4 rounded-lg shadow-md border border-gray-300 w-64">
                <div class="flex items-center">
                    <img src="{{ asset('images/periode.png') }}" alt="Kecamatan" class="w-6 h-6 mr-2">
                    <span class="text-gray-700 font-semibold" id="selectedKecamatan">
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
                class="absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                @foreach($kecamatan as $item)
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
                        onclick="selectKecamatan('{{ $item->id }}', '{{ $item->nama }}')">
                        {{ $item->nama }}
                    </a>
                @endforeach
            </div>
        </div>

    </div>


    <!-- Tabel Hasil Seleksi -->
    <div class="bg-white rounded-lg shadow-md p-4">
        @if (!empty($hasilSeleksi))
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
                                            <input type="checkbox" name="kelompok_tani_id[]" value="{{ $tani['kelompok_tani_id'] }}">
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


    <!-- Script untuk dropdown dan reload -->
    <script>
        document.getElementById('dropdownKecamatanButton').addEventListener('click', function () {
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
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('dropdownKecamatanMenu');
            const button = document.getElementById('dropdownKecamatanButton');

            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
@endsection