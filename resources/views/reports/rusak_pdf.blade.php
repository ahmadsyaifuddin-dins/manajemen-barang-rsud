<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Rusak Berat</title>
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
        <h3>LAPORAN BARANG RUSAK BERAT</h3>
         <p><small>Tanggal Cetak: {{ $tanggalCetak }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px">No</th>
                <th>Tgl Rusak</th>
                <th>Barang (Kode)</th>
                <th>Ruangan</th>
                <th>Kerusakan</th>
                <th>Status Akhir</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rusakData as $index => $item)
                @php
                    // Variabel untuk data relasi (agar lebih bersih)
                    $perbaikan = $item->perbaikan;
                    $kerusakan = $perbaikan->kerusakan ?? null;
                    $inventaris = $kerusakan->inventarisBarang ?? null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_rusak ? \Carbon\Carbon::parse($item->tanggal_rusak)->format('d-m-Y') : '' }}</td>
                    <td>{{ $inventaris->barang->nama_barang ?? 'N/A' }} ({{ $inventaris->kode_barang ?? 'N/A' }})</td>
                    <td>{{ $inventaris->ruangan->nama_ruangan ?? 'N/A' }}</td>
                    <td>{{ $kerusakan->kerusakan ?? 'N/A' }}</td>
                    <td>{{ $perbaikan->status ?? 'N/A' }}</td>
                    <td>{{ $item->keterangan_rusak }}</td>
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