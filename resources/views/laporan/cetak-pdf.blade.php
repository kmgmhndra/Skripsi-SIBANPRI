<!-- resources/views/laporan/cetak.blade.php -->
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

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">LAPORAN HASIL SELEKSI KELOMPOK TANI</div>
        <div class="subtitle">Kecamatan {{ $laporan->kecamatan }}</div>
    </div>

    <table>
        <tr>
            <td width="30%">Tanggal Seleksi</td>
            <td>{{ \Carbon\Carbon::parse($laporan->tanggal_seleksi)->format('d F Y') }}</td>
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
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kelompok_tani }}</td>
                    <td>{{ $item->nama_ketua }}</td>
                    <td>{{ $item->nama_desa }}</td>
                    <td>{{ $item->nilai_wpm }}</td>
                    <td>{{ $item->peringkat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>Dicetak pada: {{ now()->format('d F Y H:i') }}</div>
    </div>
</body>

</html>