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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-geo-alt" viewBox="0 0 16 16">
                        <path
                            d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                        <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg>
                    <span class="text-gray-700 font-semibold ml-2" id="selectedKecamatan">Memuat...</span>
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
                class="absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg hidden z-10">
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
                <br>
                Komoditas: {{ $jenisTani }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('kelompok-tani.download-template') }}"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-600 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Download Template
                </a>
                <!-- Tombol Import Data -->
                <form id="import-form" action="{{ route('kelompok-tani.import') }}" method="POST"
                    enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="hidden" id="importKecamatanId" name="kecamatan_id">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
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
                    <td colspan="{{ 3 + count($kriterias) }}" class="text-center p-4 text-gray-500">Belum ada data
                        kelompok tani.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Container Tombol Seleksi --}}
        <div class="flex justify-end gap-2 mt-4">
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
                    <input type="hidden" name="kecamatan_id" id="selectMulai" value="">
                    <input type="hidden" name="jenis_tani" value="{{ $jenisTani }}">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        Mulai
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Data --}}
    <div id="modal-tambah"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50 p-4">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg md:w-1/3">
            <h2 class="text-xl font-bold mb-4 text-center md:text-left">Input Data Kelompok Tani</h2>
            <form action="{{ route('kelompok-tani.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kecamatan_id" id="selectedKecamatanInput" class="hidden">
                <input type="hidden" name="jenis_tani" value="{{ $jenisTani }}">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">


                <div class="mb-3">
                    <input type="text" name="nama" placeholder="Nama Kelompok Tani..." class="w-full p-2 border rounded"
                        required>
                </div>
                <div class="mb-3">
                    <select name="desa" id="desaDropdown" class="w-full p-2 border rounded" required>
                        <option value="">Pilih Desa</option>
                        <!-- Desa akan diisi via JavaScript -->
                    </select>
                </div>
                <div class="mb-3">
                    <input type="text" name="ketua" placeholder="Nama Ketua..." class="w-full p-2 border rounded"
                        required>
                </div>

                @foreach ($kriterias as $kriteria)
                <div class="mb-3">
                    <input type="number" name="kriteria_value[{{$kriteria->id}}]" placeholder="{{$kriteria->nama}}"
                        class="w-full p-2 border rounded" required min="1">
                </div>
                @endforeach

                <div class="mt-4 flex flex-col md:flex-row justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded w-full md:w-auto">Batal</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded w-full md:w-auto">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Data --}}
    <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-2xl mx-4">
            <h2 class="text-xl font-bold mb-4 text-center md:text-left">Edit Data Kelompok Tani</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="kecamatan_id" id="editKecamatanId">
                <input type="hidden" name="jenis_tani" value="{{ $jenisTani }}">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <div class="space-y-3">
                    <div>
                        <label for="editNama" class="block text-sm font-medium text-gray-700">Nama Kelompok Tani</label>
                        <input type="text" name="nama" id="editNama" placeholder="Masukkan nama kelompok tani"
                            class="w-full p-2 border rounded" required>
                    </div>

                    <div>
                        <label for="editDesaDropdown" class="block text-sm font-medium text-gray-700">Desa</label>
                        <select name="desa" id="editDesaDropdown" class="w-full p-2 border rounded" required>
                            <option value="">Pilih Desa</option>
                            <!-- Desa akan diisi via JavaScript -->
                        </select>
                    </div>

                    <div>
                        <label for="editKetua" class="block text-sm font-medium text-gray-700">Nama Ketua</label>
                        <input type="text" name="ketua" id="editKetua" placeholder="Masukkan nama ketua kelompok"
                            class="w-full p-2 border rounded" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($kriterias as $kriteria)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $kriteria->nama }}</label>
                            <input type="number" name="kriteria_value_edit[{{$kriteria->id}}]"
                                id="editKriteria{{$kriteria->id}}" placeholder="Masukkan nilai {{ $kriteria->nama }}"
                                class="w-full p-2 border rounded" required min="1">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 flex flex-col md:flex-row justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded w-full md:w-auto">Batal</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded w-full md:w-auto">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Inisialisasi data desa dari backend
const allDesas = @json(\App\Models\Desa::all());

document.addEventListener("DOMContentLoaded", function() {
    let savedKecamatan = localStorage.getItem("selectedKecamatan");
    let defaultKecamatan = "{{ $kecamatan->first()->nama ?? '' }}";
    let defaultKecamatanId = "{{ $kecamatan->first()->id ?? '' }}";

    if (!savedKecamatan && defaultKecamatanId) {
        selectKecamatan(defaultKecamatanId, defaultKecamatan);
    } else if (savedKecamatan) {
        let savedKecamatanName = localStorage.getItem("selectedKecamatanName");
        selectKecamatan(savedKecamatan, savedKecamatanName);
    }

    // Event listener untuk dropdown kecamatan
    document.getElementById("dropdownKecamatanButton").addEventListener("click", () => {
        const menu = document.getElementById("dropdownKecamatanMenu");
        menu.classList.toggle("hidden");
        const icon = document.getElementById("arrowKecamatanIcon");
        icon.classList.toggle("rotate-180");
    });

    // Tutup dropdown ketika klik di luar
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdownKecamatanMenu');
        const button = document.getElementById('dropdownKecamatanButton');
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
            document.getElementById("arrowKecamatanIcon").classList.remove("rotate-180");
        }
    });
});

function selectKecamatan(id, name) {
    document.getElementById("selectedKecamatanInput").value = id;
    document.getElementById("importKecamatanId").value = id;
    document.getElementById("selectedKecamatan").innerText = name;
    document.getElementById("selectedKecamatanText").innerText = name;
    document.getElementById("currentKecamatanLabel").innerText = "Saat ini: " + name;
    document.getElementById("selectMulai").value = id;

    localStorage.setItem("selectedKecamatan", id);
    localStorage.setItem("selectedKecamatanName", name);

    // Filter tabel berdasarkan kecamatan
    filterTableByKecamatan(id);

    // Tutup dropdown
    document.getElementById("dropdownKecamatanMenu").classList.add("hidden");
    document.getElementById("arrowKecamatanIcon").classList.remove("rotate-180");
}

function filterTableByKecamatan(kecamatanId) {
    let rows = document.querySelectorAll("#table-body tr");
    rows.forEach(row => {
        if (kecamatanId === 'all') {
            row.style.display = "table-row";
        } else {
            row.style.display = String(row.getAttribute("data-kecamatan")) === String(kecamatanId) ?
                "table-row" : "none";
        }
    });
}

function showModal() {
    const kecamatanId = document.getElementById("selectedKecamatanInput").value;
    if (!kecamatanId) {
        Swal.fire('Peringatan', 'Silakan pilih kecamatan terlebih dahulu', 'warning');
        return;
    }

    loadDesaByKecamatan(kecamatanId);
    document.getElementById('modal-tambah').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal-tambah').classList.add('hidden');
}

function loadDesaByKecamatan(kecamatanId) {
    const dropdown = document.getElementById('desaDropdown');

    dropdown.innerHTML = '<option value="">Pilih Desa</option>';

    const filteredDesas = allDesas.filter(desa => desa.kecamatan_id == kecamatanId);

    filteredDesas.forEach(desa => {
        const option = document.createElement('option');
        option.value = desa.nama;
        option.textContent = desa.nama;
        dropdown.appendChild(option);
    });
}


function showModalKonfirmasi() {
    document.getElementById('modalKonfirmasi').classList.remove('hidden');
}

function hideModalKonfirmasi() {
    document.getElementById('modalKonfirmasi').classList.add('hidden');
}

function editKelompokTani(id) {
    $.get(`/kelompok-tani/${id}/edit`, function(data) {
        $('#editNama').val(data.kelompokTani.nama);
        $('#editDesaDropdown').val(data.kelompokTani.desa);
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
        loadDesaByKecamatanEdit(data.kelompokTani.kecamatan_id, data.kelompokTani.desa)


    }).fail(function() {
        alert('Gagal mengambil data!');
    });
}


function loadDesaByKecamatanEdit(kecamatanId, desa) {
    const dropdown = document.getElementById('editDesaDropdown');

    // Reset isi dropdown
    dropdown.innerHTML = '<option value="">Pilih Desa</option>';

    // Filter berdasarkan kecamatan_id
    const filteredDesas = allDesas.filter(d => d.kecamatan_id == kecamatanId);

    // Tambahkan opsi desa ke dropdown
    filteredDesas.forEach(d => {
        const option = document.createElement('option');
        option.value = d.nama;
        option.textContent = d.nama;

        // Cek apakah nama desa sesuai dengan parameter 'desa'
        if (d.nama === desa) {
            option.selected = true;
        }

        dropdown.appendChild(option);
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

<script>
document.querySelectorAll('input[name="nama"], input[name="desa"], input[name="ketua"]').forEach(input => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/[^A-Za-z\s]/g, '');
    });
});
</script>





@endsection