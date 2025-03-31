@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-start">
        <!-- Kiri: Judul & Deskripsi -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Hasil Seleksi</h1>
            <p class="text-gray-600 mt-1">Berikut adalah hasil seleksi berdasarkan kecamatan dan tanggal</p>
        </div>
    </div>

    <!-- List Card Laporan -->
    <div class="mt-6 space-y-4">
        @foreach(range(1, 5) as $item) {{-- Data dummy sementara --}}
        <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Kecamatan Contoh {{ $item }}</h2>
                <p class="text-gray-600 text-sm">Tanggal Seleksi: {{ now()->format('d M Y') }}</p>
            </div>
            <div class="flex space-x-2">
                <!-- Tombol Download -->
                <a href="{{ route('laporan.download', $item) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                    </svg>
                    Download
                </a>

                <!-- Tombol Detail -->
                <a href="{{ route('detail', $item) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Detail
                </a>

                <!-- Tombol Hapus (Memunculkan Modal) -->
                <button @click="openModal({{ $item }})" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div x-data="{ showModal: false, deleteId: null }">
    <div x-show="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold text-gray-800">Konfirmasi Hapus</h2>
            <p class="text-gray-600 mt-2">Apakah Anda yakin ingin menghapus laporan ini?</p>
            <div class="mt-4 flex justify-end space-x-2">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">Batal</button>
                <form :action="'/laporan/' + deleteId + '/hapus'" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.querySelector('[x-data]').__x.$data.showModal = true;
            document.querySelector('[x-data]').__x.$data.deleteId = id;
        }
    </script>
</div>
@endsection
