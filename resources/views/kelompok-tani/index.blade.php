@extends('layouts.app')

@section('content')
<div>
    <div class="flex justify-between items-start">
        <!-- Kiri: Judul & Deskripsi -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Kelompok Tani</h1>
            <p class="text-gray-600 mt-1">Hi, selalu update data ini ya!</p>
        </div>

        <div class="relative mb-4">
            <button id="dropdownKecamatanButton"
                class="flex flex-col items-start bg-white p-4 rounded-lg shadow-md border border-gray-300 w-64">
                <div class="flex items-center">
                    <img src="{{ asset('images/periode.png') }}" alt="Kecamatan" class="w-6 h-6 mr-2">
                    <span class="text-gray-700 font-semibold" id="selectedKecamatan">Memuat...</span>
                    <svg class="w-4 h-4 ml-auto text-gray-600 transition-transform duration-300" id="arrowKecamatanIcon"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm text-gray-500 mt-1" id="currentKecamatanLabel">Memuat...</p>
            </button>

            <!-- Dropdown Menu Kecamatan -->
            <div id="dropdownKecamatanMenu"
                class="absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                @foreach($kecamatan as $item)
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
                    onclick="selectKecamatan('{{ $item->id }}', '{{ $item->nama }}')">{{ $item->nama }}</a>
                @endforeach
            </div>
        </div>
    </div>


    {{-- Card Data Kelompok Tani --}}
    <div class="bg-white p-6 shadow rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">
                Input Data Kelompok Tani Kecamatan <span id="selectedKecamatanText">Memuat...</span>
            </h2>
            <div class="flex gap-2">
                {{-- Tombol Import Data --}}
                <button onclick="document.getElementById('file-import').click()"
                    class="bg-gray-200 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-300">
                    📂 Import
                </button>
                <button onclick="showModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    + Tambah Data
                </button>
                <input type="file" id="file-import" class="hidden" onchange="importExcel(event)">
            </div>
        </div>

        {{-- Tabel Data --}}
        <table class="w-full border-collapse border text-sm">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border p-2">Nama Kelompok</th>
                    <th class="border p-2">Nama Ketua</th>
                    <th class="border p-2">Desa</th>
                    <th class="border p-2">Simluhtan</th>
                    <th class="border p-2">Terpoligon</th>
                    <th class="border p-2">Bantuan Sebelumnya</th>
                    <th class="border p-2">DPI</th>
                    <th class="border p-2">Provitas</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @forelse($kelompokTani as $item)
                <tr class="border" data-kecamatan="{{ $item->kecamatan_id }}">
                    <td class="border p-2">{{ $item->nama }}</td>
                    <td class="border p-2">{{ $item->ketua }}</td>
                    <td class="border p-2">{{ $item->desa }}</td>
                    <td class="border p-2">{{ $item->simluhtan }}</td>
                    <td class="border p-2">{{ $item->terpoligon }}</td>
                    <td class="border p-2">{{ $item->bantuan_sebelumnya }}</td>
                    <td class="border px-4 py-2">
                        {{ number_format($item->dpi, ($item->dpi == floor($item->dpi)) ? 0 : 2) }}
                    </td>
                    <td class="border px-4 py-2">
                        {{ number_format($item->provitas, ($item->provitas == floor($item->provitas)) ? 0 : 2) }}
                    </td>
                    <td class="border p-2">
                        <button onclick="editKelompokTani({{ $item->id }})"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </button>
                        <button onclick="hapusKelompokTani({{ $item->id }})"
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-4 text-gray-500">Belum ada data kelompok tani.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Container Tombol Seleksi --}}
        <div class="flex justify-end gap-2 mt-4">
            {{-- Tombol Mulai Seleksi --}}
            <button onclick="showModalKonfirmasi()"
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                {{ count($kelompokTani) == 0 ? 'disabled' : '' }}>
                Mulai Seleksi
            </button>


        </div>
    </div>

    {{-- Modal Konfirmasi Mulai Seleksi --}}
    <div id="modalKonfirmasi" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50  ">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Mulai Seleksi</h2>
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin memulai proses seleksi?</p>
            <div class="flex justify-end gap-2">
                <button onclick="hideModalKonfirmasi()"
                    class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                <form id="seleksiForm" action="{{ route('seleksi.proses') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        Mulai
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function showModalKonfirmasi() {
        document.getElementById('modalKonfirmasi').classList.remove('hidden');
    }

    function hideModalKonfirmasi() {
        document.getElementById('modalKonfirmasi').classList.add('hidden');
    }
    </script>



</div>

{{-- Modal Tambah Data --}}
<div id="modal-tambah" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Input Data Kelompok Tani</h2>
        <form action="{{ route('kelompok-tani.store') }}" method="POST">
            @csrf
            <input type="text" name="kecamatan_id" id="selectedKecamatanInput" class="hidden">

            <div class="mb-3">
                <input type="text" name="nama" placeholder="Nama Kelompok Tani..." class="w-full p-2 border rounded"
                    required>
            </div>
            <div class="mb-3">
                <input type="text" name="desa" placeholder="Nama Desa..." class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <input type="text" name="ketua" placeholder="Nama Ketua..." class="w-full p-2 border rounded" required>
            </div>

            {{-- Dropdown Fields --}}
            <div class="grid grid-cols-3 gap-2">
                <select name="simluhtan" class="p-2 border rounded">
                    <option value="">Simluhtan</option>
                    <option value="1">1</option>
                    <option value="5">5</option>
                </select>

                <select name="terpoligon" class="p-2 border rounded">
                    <option value="">Terpoligon</option>
                    <option value="1">1</option>
                    <option value="5">5</option>
                </select>

                <select name="bantuan_sebelumnya" class="p-2 border rounded">
                    <option value="">Bantuan Sebelumnya</option>
                    <option value="1">1</option>
                    <option value="5">5</option>
                </select>

                <input type="number" name="dpi" placeholder="DPI..." class="p-2 border rounded" required>
                <input type="number" name="provitas" placeholder="Provitas..." class="p-2 border rounded" required>

                <input type="hidden" name="kecamatan_id" id="selectedKecamatanInput">

            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Data --}}
<div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Edit Data Kelompok Tani</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <!-- Tambahkan method PUT -->
            <input type="hidden" name="kecamatan_id" id="editKecamatanId">

            <div class="mb-3">
                <input type="text" name="nama" id="editNama" placeholder="Nama Kelompok Tani..."
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <input type="text" name="desa" id="editDesa" placeholder="Nama Desa..."
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <input type="text" name="ketua" id="editKetua" placeholder="Nama Ketua..."
                    class="w-full p-2 border rounded" required>
            </div>

            {{-- Dropdown Fields --}}
            <div class="grid grid-cols-3 gap-2">
                <select name="simluhtan" id="editSimluhtan" class="p-2 border rounded">
                    <option value="">Simluhtan</option>
                    <option value="1">1</option>
                    <option value="5">5</option>
                </select>

                <select name="terpoligon" id="editTerpoligon" class="p-2 border rounded">
                    <option value="">Terpoligon</option>
                    <option value="1">1</option>
                    <option value="5">5</option>
                </select>

                <select name="bantuan_sebelumnya" id="editBantuanSebelumnya" class="p-2 border rounded">
                    <option value="">Bantuan Sebelumnya</option>
                    <option value="1">1</option>
                    <option value="5">5</option>
                </select>

                <input type="number" name="dpi" id="editDpi" placeholder="DPI..." class="p-2 border rounded" required>
                <input type="number" name="provitas" id="editProvitas" placeholder="Provitas..."
                    class="p-2 border rounded" required>

            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let savedKecamatan = localStorage.getItem("selectedKecamatan");
    let defaultKecamatan = "Gerokgak";
    let defaultKecamatanId = "{{ $kecamatan->first()->id ?? '' }}";

    if (!savedKecamatan) {
        selectKecamatan(defaultKecamatanId, defaultKecamatan);
    } else {
        let savedKecamatanName = localStorage.getItem("selectedKecamatanName");
        selectKecamatan(savedKecamatan, savedKecamatanName);
    }
});

function selectKecamatan(id, name) {
    console.log(id, name);
    document.getElementById("selectedKecamatanInput").value = id;
    document.getElementById("selectedKecamatan").innerText = name;
    document.getElementById("selectedKecamatanText").innerText = name;
    document.getElementById("currentKecamatanLabel").innerText = "Saat ini: " + name;
    localStorage.setItem("selectedKecamatan", id);
    localStorage.setItem("selectedKecamatanName", name);
    document.getElementById("dropdownKecamatanMenu").classList.add("hidden");
    filterTableByKecamatan(id);
}

function filterTableByKecamatan(kecamatanId) {
    let rows = document.querySelectorAll("#table-body tr");
    rows.forEach(row => {
        row.style.display = String(row.getAttribute("data-kecamatan")) === String(kecamatanId) ? "table-row" :
            "none";
    });
}

document.getElementById("dropdownKecamatanButton").addEventListener("click", () => {
    document.getElementById("dropdownKecamatanMenu").classList.toggle("hidden");
});

function showModal() {
    document.getElementById('modal-tambah').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal-tambah').classList.add('hidden');
}


function importExcel(event) {
    alert('Import file: ' + event.target.files[0].name);
}

// Format angka agar bilangan bulat tanpa desimal, bilangan pecahan dengan 2 desimal


function editKelompokTani(id) {
    $.get(`/kelompok-tani/${id}/edit`, function(data) {
        console.log(data); // Debugging data
        // Isi form edit dengan data yang diambil
        $('#editNama').val(data.kelompokTani.nama);
        $('#editDesa').val(data.kelompokTani.desa);
        $('#editKetua').val(data.kelompokTani.ketua);
        $('#editSimluhtan').val(data.kelompokTani.simluhtan);
        $('#editTerpoligon').val(data.kelompokTani.terpoligon);
        $('#editBantuanSebelumnya').val(data.kelompokTani.bantuan_sebelumnya);

        $('#editDpi').val(data.kelompokTani.dpi);
        $('#editProvitas').val(data.kelompokTani.provitas);

        $('#editKecamatanId').val(data.kelompokTani.kecamatan_id);

        // Set action form edit
        $('#editForm').attr('action', `/kelompok-tani/${id}`);

        // Tampilkan modal edit
        $('#editModal').removeClass('hidden');
    }).fail(function() {
        alert('Gagal mengambil data!');
    });
}


function closeEditModal() {
    $('#editModal').addClass('hidden');
}


// Submit edit form
$('#editForm').submit(function(e) {
    e.preventDefault();

    let formData = $(this).serialize();
    let actionUrl = $(this).attr('action');

    $.ajax({
        url: actionUrl,
        type: 'POST', // Laravel tidak menerima PUT langsung, gunakan _method
        data: formData + "&_method=PUT",
        success: function(response) {
            $('#editModal').addClass('hidden');
            Swal.fire('Sukses!', 'Data berhasil diperbarui!', 'success');
            location.reload();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            Swal.fire('Error!', 'Terjadi kesalahan saat update!', 'error');
        }
    });
});

function hapusKelompokTani(id) {
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
                url: `/kelompok-tani/${id}`,
                type: 'POST', // Laravel hanya mendukung POST, gunakan _method untuk DELETE
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content') // Gunakan meta tag CSRF
                },
                success: function(response) {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus!', 'success');
                    location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    Swal.fire('Error!', 'Gagal menghapus data!', 'error');
                }
            });
        }
    });
}
</script>

@endsection