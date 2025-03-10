@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Daftar Kriteria</h1>

    <!-- Form Tambah Kriteria -->
    <form id="formTambah" class="flex gap-2 mb-6">
        @csrf
        <input type="text" id="namaKriteria" name="nama" placeholder="Nama Kriteria" class="border p-2 flex-1 rounded"
            required>
        <input type="number" id="urutanKriteria" name="urutan" placeholder="Urutan" class="border p-2 w-20 rounded"
            min="1" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan
        </button>
    </form>


    <!-- Tabel Kriteria -->
    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Urutan</th>
                <th class="border p-2">Nama Kriteria</th>
                <th class="border p-2">Bobot ROC</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody id="kriteriaTbody">
            @foreach ($kriteria as $k)
            <tr data-id="{{ $k->id }}">
                <td class="border p-2">{{ $k->urutan }}</td>
                <td class="border p-2">{{ $k->nama }}</td>
                <td class="border p-2">{{ number_format($k->bobot, 4) }}</td>
                <td class="border p-2">
                    <button onclick="editKriteria({{ $k->id }}, '{{ $k->nama }}')"
                        class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                    <button onclick="hapusKriteria({{ $k->id }}, '{{ $k->nama }}')"
                        class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button>
                </td>
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
            @csrf
            <input type="hidden" id="editId">
            <input type="text" id="editNama" name="nama" class="border p-2 w-full rounded mb-4" required>
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
document.getElementById('formTambah').addEventListener('submit', function(e) {
    e.preventDefault();
    let nama = document.getElementById('namaKriteria').value;
    let urutan = document.getElementById('urutanKriteria').value;

    fetch("{{ route('kriteria.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                nama,
                urutan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            }
        })
        .catch(() => {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Terjadi kesalahan",
                timer: 2000,
                showConfirmButton: false
            });
        });
});


// Edit Kriteria
function editKriteria(id, nama) {
    document.getElementById('editId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('editId').value;
    let nama = document.getElementById('editNama').value;

    fetch(`/kriteria/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                nama
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            }
        })
        .catch(() => {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Terjadi kesalahan",
                timer: 2000,
                showConfirmButton: false
            });
        });
});

// Hapus Kriteria
function hapusKriteria(id, nama) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: `Kriteria "${nama}" akan dihapus!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/kriteria/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Terjadi kesalahan",
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
        }
    });
}
</script>
@endpush