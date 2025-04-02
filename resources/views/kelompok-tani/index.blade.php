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
                <a href="" class="block px-4 py-2 text-gray-700 hover:bg-blue-100"
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
                <br>

                Jenis Tani: {{ $jenisTani }}

            </h2>
            <div class="flex gap-2">
                <!-- Tombol Import Data -->
                <form id="import-form" action="{{ route('kelompok-tani.import') }}" method="POST"
                    enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="text" id="importKecamatanId" name="kecamatan_id">
                    <input type="file" id="file-import" name="file" accept=".xlsx,.xls,.csv">
                </form>

                <button onclick="document.getElementById('file-import').click()"
                    class="bg-gray-200 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-300 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Import Data
                </button>

                <button onclick="showModal()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Data
                </button>
            </div>


        </div>

        {{-- Tabel Data --}}
        <table class="w-full border-collapse border text-sm">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border p-2">Nama Kelompok</th>
                    <th class="border p-2">Nama Ketua</th>
                    <th class="border p-2">Desa</th>

                    @foreach ($kriterias as $kriteria)
                    <th class="border p-2">{{$kriteria->nama}}</th>

                    @endforeach


                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @forelse($kelompokTani as $item)
                <tr class="border" data-kecamatan="{{ $item->kecamatan_id }}">
                    <td class="border p-2">{{ $item->nama }}</td>
                    <td class="border p-2">{{ $item->ketua }}</td>
                    <td class="border p-2">{{ $item->desa }}</td>

                    @foreach ($kriterias as $kriteria)
                    <td class="border p-2 text-center">
                        {{ $kriteriaValuesArray[$item->id][$kriteria->id] ?? '-' }}
                    </td>
                    @endforeach

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
            @auth
            @if(auth()->user()->role === 'admin')
            <button onclick="showModalKonfirmasi()"
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                {{ count($kelompokTani) == 0 ? 'disabled' : '' }}>
                Mulai Seleksi
            </button>
            @endif
            @endauth


        </div>
    </div>

    <!-- Modal Konfirmasi Mulai Seleksi -->
    <div id="modalKonfirmasi" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Mulai Seleksi</h2>
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin memulai proses seleksi?</p>
            <div class="flex justify-end gap-2">
                <button onclick="hideModalKonfirmasi()"
                    class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                <form id="seleksiForm" action="{{ route('seleksi.proses') }}" method="POST">
                    @csrf
                    <!-- Input hidden untuk mengirim kecamatan_id -->
                    <input type="hidden" name="kecamatan_id" id="selectMulai" value="">
                    <input type="hidden" name="jenis_tani" id="selectMulai" value="{{ $jenisTani }}">
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
            <input type="hidden" name="kecamatan_id" id="selectedKecamatanInput" class="hidden">


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


            @foreach ($kriterias as $kriteria)
            <div class="mb-3">
                <input type="number" name="kriteria_value[{{$kriteria->id}}]" placeholder="{{$kriteria->nama}}"
                    class="w-full p-2 border rounded" required>
            </div>
            @endforeach

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
            <input type="hidden" name="kecamatan_id" id="editKecamatanId">

            <div class="mb-3">
                <label for="editNama" class="block text-sm font-medium text-gray-700">Nama Kelompok Tani</label>
                <input type="text" name="nama" id="editNama" placeholder="Masukkan nama kelompok tani"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-3">
                <label for="editDesa" class="block text-sm font-medium text-gray-700">Nama Desa</label>
                <input type="text" name="desa" id="editDesa" placeholder="Masukkan nama desa"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-3">
                <label for="editKetua" class="block text-sm font-medium text-gray-700">Nama Ketua</label>
                <input type="text" name="ketua" id="editKetua" placeholder="Masukkan nama ketua kelompok"
                    class="w-full p-2 border rounded" required>
            </div>

            <!-- Dropdown Fields -->
            <div class="grid grid-cols-3 gap-2">
                @foreach ($kriterias as $kriteria)
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">{{ $kriteria->nama }}</label>
                    <input type="number" name="kriteria_value_edit[{{$kriteria->id}}]"
                        placeholder="Masukkan nilai {{ $kriteria->nama }}" class="p-2 border rounded w-full" required>
                </div>
                @endforeach
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
    const link = document.getElementById('selectedKecamatanRequest');
    link.href = "{{ route('hasil-seleksi.index') }}?kecamatan_id=" + id;
    console.log(id, name);
    document.getElementById("selectedKecamatanInput").value = id;
    document.getElementById("importKecamatanId").value = id;
    document.getElementById("selectedKecamatan").innerText = name;
    document.getElementById("selectedKecamatanText").innerText = name;
    document.getElementById("currentKecamatanLabel").innerText = "Saat ini: " + name;
    localStorage.setItem("selectedKecamatan", id);
    localStorage.setItem("selectedKecamatanName", name);
    document.getElementById("selectMulai").value = id;


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



// Format angka agar bilangan bulat tanpa desimal, bilangan pecahan dengan 2 desimal


function editKelompokTani(id) {
    $.get(`/kelompok-tani/${id}/edit`, function(data) {
        console.log(data); // Debugging data di console

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

        // Isi nilai kriteria
        console.log(data.kriteriaValues);

        if (data.kriteriaValues) {
            data.kriteriaValues.forEach((item) => {
                $(`input[name='kriteria_value_edit[${item.kriteria_id}]']`).val(item.value ? item
                    .value : "");
            });

        }


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
        type: 'POST',
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


document.getElementById('file-import').addEventListener('change', function() {
    if (this.files.length > 0) {
        // Immediately submit the form when file is selected
        document.getElementById('import-form').submit();

        // Optional: Show a simple loading state
        const importBtn = document.querySelector('[onclick*="file-import"]');
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengimpor...';
        importBtn.disabled = true;

        // Reset button after 3 seconds in case submission fails
        setTimeout(() => {
            importBtn.innerHTML = '<i class="fas fa-upload"></i> Import Data';
            importBtn.disabled = false;
        }, 3000);
    }
});
</script>




@endsection