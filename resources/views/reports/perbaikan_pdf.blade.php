<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perbaikan Barang</title>
    <style>
        /* Salin style CSS dari view PDF sebelumnya */
        body { font-family: sans-serif; font-size: 11px; }
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
        <h3>LAPORAN PERBAIKAN BARANG</h3>
         <p><small>Tanggal Cetak: {{ $tanggalCetak }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px">No</th>
                <th>Tgl Perbaikan</th>
                <th>Barang (Kode)</th>
                <th>Ruangan</th>
                <th>Laporan Kerusakan</th>
                <th>Status</th>
                <th>Kondisi Selesai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perbaikanData as $index => $item)
                @php
                    // Variabel untuk data relasi (agar lebih bersih)
                    $kerusakan = $item->kerusakan;
                    $inventaris = $kerusakan->inventarisBarang ?? null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_perbaikan ? \Carbon\Carbon::parse($item->tanggal_perbaikan)->format('d-m-Y') : '' }}</td>
                    <td>{{ $inventaris->barang->nama_barang ?? 'N/A' }} ({{ $inventaris->kode_barang ?? 'N/A' }})</td>
                    <td>{{ $inventaris->ruangan->nama_ruangan ?? 'N/A' }}</td>
                    <td>{{ $kerusakan->kerusakan ?? 'N/A' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->kondisi_perbaikan }}</td>
                    <td>{{ $item->keterangan }}</td>
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