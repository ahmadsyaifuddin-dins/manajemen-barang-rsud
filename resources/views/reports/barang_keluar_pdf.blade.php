<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Keluar Gudang</title>
    <style>
        /* Salin style CSS dari view PDF sebelumnya */
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        {{-- Salin Kop Surat --}}
        <p>PEMERINTAH KABUPATEN TABALONG<br>
        <b>RUMAH SAKIT UMUM DAERAH H. BADARUDDIN KASIM</b><br>
        <small>Jalan... Telp...</small></p>
        <hr>
        <h3>LAPORAN BARANG KELUAR GUDANG</h3>
         <p><small>Tanggal Cetak: {{ $tanggalCetak }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Tanggal Keluar</th>
                <th>Jumlah</th>
                <th>Ruangan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($keluarData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    {{-- Akses relasi berantai --}}
                    <td>{{ $item->gudangStok->barangGudang->nama_barang_gudang ?? 'N/A' }}</td>
                    <td>{{ $item->gudangStok->barangGudang->kategori_barang_gudang ?? 'N/A' }}</td>
                    <td>{{ $item->tanggal_keluar ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') : '' }}</td>
                    <td>{{ $item->jumlah_keluar }}</td>
                    {{-- Akses relasi langsung --}}
                    <td>{{ $item->ruangan->nama_ruangan ?? 'N/A' }}</td>
                    <td>{{ $item->keterangan_keluar }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>