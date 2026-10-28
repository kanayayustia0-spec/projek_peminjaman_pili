<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Peminjaman Aset PILI - Admin</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #333333;
            padding: 7px;
            vertical-align: top;
        }
        th {
            background-color: #145391;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }
        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #145391;
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
            <td colspan="12" class="header-title">YAYASAN PILI GREEN NETWORK</td>
        </tr>
        <tr>
            <td colspan="12" class="header-sub">REKAPITULASI DATA PEMINJAMAN ASET & INVENTARIS</td>
        </tr>
        <tr>
            <td colspan="12" class="header-sub">Jl. Tumenggung Wiradireja No.216, RT.06/RW.09, Cimahpar, Kec. Bogor Utara, Kota Bogor, Jawa Barat 16155 | Email: piligreennetwork@gmail.com</td>
        </tr>
        <tr>
            <td colspan="12" class="header-sub">Tanggal Download: {{ date('d-m-Y H:i:s') }} WIB</td>
        </tr>
        <tr><td colspan="12" style="border:none; height: 12px;"></td></tr>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Transaksi</th>
                <th>Nama Peminjam</th>
                <th>Divisi / Program</th>
                <th>No. WhatsApp</th>
                <th>Nama Kegiatan</th>
                <th>Lokasi</th>
                <th>Jadwal Mulai</th>
                <th>Jadwal Selesai</th>
                <th>Rincian Aset (Kode - Nama - Qty - Kat)</th>
                <th>Approval (Pengelola / Persetujuan)</th>
                <th>Status Keseluruhan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center text-bold">{{ $row->nomor_transaksi }}</td>
                    <td class="text-bold">{{ $row->user->name ?? 'User' }}</td>
                    <td>{{ $row->user->divisi_program ?? '-' }}</td>
                    <td>{{ $row->user->no_hp ?? '-' }}</td>
                    <td class="text-bold">{{ $row->nama_kegiatan }}</td>
                    <td>{{ $row->lokasi_penggunaan }}</td>
                    <td class="text-center">{{ $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-center">{{ $row->tanggal_selesai ? $row->tanggal_selesai->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        @foreach($row->items as $item)
                            • {{ $item->barang->kode_inventaris ?? '-' }} - {{ $item->barang->nama ?? 'Aset' }} ({{ $item->jumlah }} unit) [Kategori {{ $item->kategori_saat_pinjam }}]<br>
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
                    <td colspan="12" class="text-center">Belum ada transaksi peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
