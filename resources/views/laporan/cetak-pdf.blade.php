<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Seleksi - {{ $laporan->kecamatan }}</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo {
        width: 80px;
        height: auto;
        position: absolute;
        top: 10px;
        left: 20px;
    }

    .kop-surat {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .line {
        border-bottom: 3px solid black;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .footer {
        margin-top: 30px;
        text-align: right;
        font-size: 12px;
    }
    </style>
</head>

<body>
    <!-- <img src="{{ public_path('images/logo1.png') }}" class="logo" alt="Logo"
        style="margin-top: -20px; margin-left: -10px;">
    <div class="kop-surat">
        <div style="font-size: 16px; font-weight: bold;">PEMERINTAH KABUPATEN BULELENG</div>
            <div style="font-size: 18px; font-weight: bold;">DINAS PERTANIAN KABUPATEN BULELENG</div>
            <div style="font-size: 14px;">Jl. A. Yani No.99, Kaliuntu, Kec. Buleleng, Kabupaten Buleleng, Bali 81116
            </div>
            <div style="font-size: 14px;">Email: distan@bulelengkab.go.id | Telp: (0362) 25090</div>
    </div> -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
        <!-- Logo Kiri -->
        <img src="{{ public_path('images/logo1.png') }}" class="logo" alt="Logo"
            style="height: 80px; margin-top: -10px; margin-left: -10px;">

        <!-- Kop Surat (Tengah) -->
        <div style="text-align: center; flex-grow: 1;">
            <div style="font-size: 16px; font-weight: bold;">PEMERINTAH KABUPATEN BULELENG</div>
            <div style="font-size: 18px; font-weight: bold;">DINAS PERTANIAN KABUPATEN BULELENG</div>
            <div style="font-size: 14px;">Jl. A. Yani No.99, Kaliuntu, Kec. Buleleng, Kabupaten Buleleng, Bali 81116
            </div>
            <div style="font-size: 14px;">Email: distan@bulelengkab.go.id | Telp: (0362) 25090</div>
        </div>

    </div>

    <!-- Garis Bawah -->
    <div style="border-bottom: 2px solid black; margin-top: 5px; margin-bottom: 10px;"></div>


    <div class="header">
        <div class="title">LAPORAN HASIL SELEKSI KELOMPOK TANI</div>
        <div class="subtitle">Kecamatan {{ $laporan->kecamatan }}</div>
    </div>

    <table>
        <tr>
            <td width="30%">Tanggal Seleksi</td>
            <td> {{ \Carbon\Carbon::parse($subLaporans->first()->laporan->tanggal_seleksi)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td>Jumlah Kelompok Tani</td>
            <td>{{ $laporan->jumlah_kelompok_tani }} Kelompok</td>
        </tr>
        <tr>
            <td>Jenis Tani</td>
            <td>{{ $laporan->jenis_tani }}</td>
        </tr>
    </table>

    <h3 style="margin-top: 20px;">Daftar Kelompok Tani Terpilih</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kelompok Tani</th>
                <th>Nama Ketua</th>
                <th>Desa</th>
                <th>Nilai WPM</th>
                <th>Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subLaporans as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->nama_kelompok_tani }}</td>
                <td>{{ $item->nama_ketua }}</td>
                <td>{{ $item->nama_desa }}</td>
                <td style="text-align: center;">{{ $item->nilai_wpm }}</td>
                <td style="text-align: center;">{{ $item->peringkat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>Dicetak pada: {{ now()->format('d F Y H:i') }}</div>
    </div>
</body>

</html>