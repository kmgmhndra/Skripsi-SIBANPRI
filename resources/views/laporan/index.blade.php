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
            @forelse($laporans as $laporan)
                <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">{{ $laporan->nama_laporan }}</h2>
                        <p class="text-gray-600 text-sm">Tanggal Seleksi:
                            {{ \Carbon\Carbon::parse($laporan->tanggal_seleksi)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Tombol Download -->
                        <a href="{{ route('laporan.download', $laporan->id) }}"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v12m0 0l-4-4m4 4l4-4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                            </svg>
                            Download
                        </a>

                        <!-- Tombol Detail -->
                        <a href="{{ route('detail', $laporan->id) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Detail
                        </a>

                        <!-- Tombol Hapus (Memunculkan Modal) -->
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <button onclick="hapusLaporan({{ $laporan->id }})"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 text-center">
                    <p class="text-gray-600">Belum ada laporan yang tersedia</p>
                </div>
            @endforelse
        </div>
    </div>





    <script>
        function hapusLaporan(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/laporan-delete/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE', // Changed to DELETE
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire('Terhapus!', response.message, 'success');
                            location.reload();
                        },
                        error: function (xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal menghapus data!', 'error');
                        }
                    });
                }
            });
        }
    </script>

@endsection