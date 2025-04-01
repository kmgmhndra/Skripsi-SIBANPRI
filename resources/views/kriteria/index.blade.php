@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Daftar Kriteria</h1>

        <!-- Form Tambah Kriteria -->
        @auth
            @if(auth()->user()->role === 'admin')

                <form id="formTambah" class="flex gap-2 mb-6">
                    <input type="text" id="namaKriteria" name="nama" placeholder="Nama Kriteria" class="border p-2 flex-1 rounded"
                        required>
                    <input type="number" id="urutanKriteria" name="urutan" placeholder="Urutan" class="border p-2 w-20 rounded"
                        min="1" required>
                    <select id="jenisKriteria" name="jenis" class="border p-2 rounded">
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                </form>
            @endif
        @endauth



        <!-- Tabel Kriteria -->
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Urutan</th>
                    <th class="border p-2">Nama Kriteria</th>
                    <th class="border p-2">Bobot ROC</th>
                    <th class="border p-2">Jenis</th>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <th class="border p-2">Aksi</th>
                        @endif
                    @endauth

                </tr>
            </thead>
            <tbody id="kriteriaTbody">
                @foreach ($kriteria as $k)
                    <tr data-id="{{ $k->id }}">
                        <td class="border p-2">{{ $k->urutan }}</td>
                        <td class="border p-2">{{ $k->nama }}</td>
                        <td class="border p-2">{{ number_format($k->bobot, 4) }}</td>
                        <td class="border p-2">{{ $k->jenis }}</td>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <td class="border p-2">

                                    <button onclick="editKriteria({{ $k->id }}, '{{ $k->nama }}', '{{ $k->jenis }}', {{ $k->urutan }})"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                                    <button onclick="hapusKriteria({{ $k->id }}, '{{ $k->nama }}')"
                                        class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Edit Kriteria</h2>
            <form id="editForm">
                <input type="hidden" id="editId">
                <label for="editUrutan" class="block text-gray-700">Urutan:</label>
                <input type="number" id="editUrutan" name="urutan" class="border p-2 w-full rounded mb-4" required>
                <label for="editNama" class="block text-gray-700">Nama Kriteria:</label>
                <input type="text" id="editNama" name="nama" class="border p-2 w-full rounded mb-4" required>
                <label for="editJenis" class="block text-gray-700">Jenis Kriteria:</label>
                <select id="editJenis" name="jenis" class="border p-2 w-full rounded mb-4">
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tambah Kriteria
        document.getElementById('formTambah').addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch("{{ url('/kriteria') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: data.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                })
                .catch(() => Swal.fire("Error!", "Terjadi kesalahan", "error"));
        });

        // Edit Kriteria
        function editKriteria(id, nama, jenis, urutan) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editJenis').value = jenis;
            document.getElementById('editUrutan').value = urutan;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            let id = document.getElementById('editId').value;

            let formData = new FormData();
            formData.append('nama', document.getElementById('editNama').value);
            formData.append('jenis', document.getElementById('editJenis').value);
            formData.append('urutan', document.getElementById('editUrutan').value);
            formData.append('_method', 'PUT'); // Laravel menerima PUT melalui POST

            fetch(`/kriteria/${id}`, {
                method: "POST", // Laravel butuh POST jika pakai FormData
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: data.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire("Error!", data.message, "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Terjadi kesalahan", "error"));
        });

        function hapusKriteria(id) {
            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "Apakah Anda yakin ingin menghapus kriteria ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/kriteria/${id}`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Terhapus!",
                                    text: "Kriteria berhasil dihapus.",
                                    icon: "success",
                                    timer: 1500, // Notifikasi otomatis hilang setelah 1.5 detik
                                    showConfirmButton: false // Hilangkan tombol OK
                                });
                                setTimeout(() => location.reload(), 1500); // Reload setelah notifikasi hilang
                            } else {
                                Swal.fire("Gagal!", "Gagal menghapus kriteria: " + data.message, "error");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire("Error!", "Terjadi kesalahan saat menghapus kriteria.", "error");
                        });
                }
            });
        }




    </script>
@endpush