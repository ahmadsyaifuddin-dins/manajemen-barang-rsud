<!DOCTYPE html>
<html>
<head>
    <title>Laporan Permintaan Perbaikan</title>
    <style>
        /* Salin style CSS dari view PDF sebelumnya */
        body { font-family: sans-serif; font-size: 11px; } /* Ukuran font lebih kecil */
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
        <h3>LAPORAN PERMINTAAN PERBAIKAN BARANG</h3>
         <p><small>Tanggal Cetak: {{ $tanggalCetak }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px">No</th>
                <th>Tgl Lapor</th>
                <th>Ruangan</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Kerusakan</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kerusakanData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '' }}</td>
                    {{-- Akses relasi berantai --}}
                    <td>{{ $item->inventarisBarang->ruangan->nama_ruangan ?? 'N/A' }}</td>
                    <td>{{ $item->inventarisBarang->barang->nama_barang ?? 'N/A' }}</td>
                    <td>{{ $item->inventarisBarang->kode_barang ?? 'N/A' }}</td>
                    <td>{{ $item->kerusakan }}</td>
                    <td>{{ $item->status_kerusakan }}</td>
                    <td>{{ $item->keterangan_kerusakan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>