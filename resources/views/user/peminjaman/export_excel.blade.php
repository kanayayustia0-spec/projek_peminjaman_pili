<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Peminjaman Aset PILI</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }
        th, td {
            border: 1px solid #333333;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #1A6BB8;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }
        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1A6BB8;
            text-align: center;
            border: none;
        }
        .header-sub {
            font-size: 11pt;
            color: #555555;
            text-align: center;
            border: none;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="10" class="header-title">YAYASAN PILI GREEN NETWORK</td>
        </tr>
        <tr>
            <td colspan="10" class="header-sub">DAFTAR RIWAYAT PEMINJAMAN ASET INVENTARIS</td>
        </tr>
        <tr>
            <td colspan="10" class="header-sub">Jl. Tumenggung Wiradireja No.216, RT.06/RW.09, Cimahpar, Kec. Bogor Utara, Kota Bogor, Jawa Barat 16155 | Email: piligreennetwork@gmail.com</td>
        </tr>
        <tr>
            <td colspan="10" class="header-sub">Pemohon: {{ Auth::user()->name }} | Tanggal Cetak: {{ date('d-m-Y H:i') }} WIB</td>
        </tr>
        <tr><td colspan="10" style="border:none; height: 15px;"></td></tr>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Transaksi</th>
                <th>Nama Kegiatan</th>
                <th>Tujuan / Urgensi</th>
                <th>Lokasi Penggunaan</th>
                <th>Jadwal Mulai</th>
                <th>Jadwal Selesai</th>
                <th>Daftar Aset Dipinjam</th>
                <th>Status Persetujuan</th>
                <th>Status Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center text-bold">{{ $row->nomor_transaksi }}</td>
                    <td class="text-bold">{{ $row->nama_kegiatan }}</td>
                    <td>{{ $row->tujuan_peminjaman }}</td>
                    <td>{{ $row->lokasi_penggunaan }}</td>
                    <td class="text-center">{{ $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-center">{{ $row->tanggal_selesai ? $row->tanggal_selesai->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        @foreach($row->items as $item)
                            • {{ $item->barang->nama ?? 'Aset' }} ({{ $item->barang->kode_inventaris ?? '-' }}) - {{ $item->jumlah }} unit [Kat {{ $item->kategori_saat_pinjam }}]<br>
                        @endforeach
                    </td>
                    <td class="text-center">
                        Pengelola: {{ $row->status_pengelola }}<br>
                        Persetujuan: {{ $row->status_persetujuan }}
                    </td>
                    <td class="text-center text-bold">{{ $row->status_keseluruhan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Belum ada riwayat transaksi peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
