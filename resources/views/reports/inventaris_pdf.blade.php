<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 50px; /* Sesuaikan ukuran logo */ }
        /* Tambahkan style lain sesuai kebutuhan */
    </style>
</head>
<body>
    <div class="header">
        {{-- Tambahkan Kop Surat di sini jika perlu (mirip file cetak PHP) --}}
        {{-- <img src="{{ public_path('path/ke/logo_kab.png') }}" alt="Logo Kab"> --}}
        <p>PEMERINTAH KABUPATEN TABALONG<br>
        <b>RUMAH SAKIT UMUM DAERAH H. BADARUDDIN KASIM</b><br>
        <small>Jalan... Telp...</small></p>
        {{-- <img src="{{ public_path('path/ke/logo_rsud.png') }}" alt="Logo RSUD"> --}}
        <hr>
        <h3>LAPORAN INVENTARIS BARANG</h3>
         <p><small>Tanggal Cetak: {{ $tanggalCetak }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th>Ruangan</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Kode Barang</th>
                <th>Tanggal Masuk</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inventarisData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->ruangan->nama_ruangan ?? 'N/A' }}</td>
                    <td>{{ $item->barang->nama_barang ?? 'N/A' }}</td>
                    <td>{{ $item->barang->jenis_barang ?? 'N/A' }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '' }}</td>
                    <td>{{ $item->kondisi }}</td>
                    <td>{{ $item->keterangan_inventaris }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tambahkan bagian tanda tangan jika perlu --}}

</body>
</html>