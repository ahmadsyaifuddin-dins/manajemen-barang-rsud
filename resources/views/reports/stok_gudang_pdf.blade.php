<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Gudang</title>
    <style>
        /* Salin style CSS dari inventaris_pdf.blade.php */
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        /* ... style lainnya ... */
    </style>
</head>
<body>
    <div class="header">
        {{-- Salin Kop Surat dari inventaris_pdf.blade.php --}}
        <p>PEMERINTAH KABUPATEN TABALONG<br>
        <b>RUMAH SAKIT UMUM DAERAH H. BADARUDDIN KASIM</b><br>
        <small>Jalan... Telp...</small></p>
        <hr>
        <h3>LAPORAN STOK BARANG GUDANG</h3>
         <p><small>Tanggal Cetak: {{ $tanggalCetak }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Kategori</th>
                <th>Jumlah Stok</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stokData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    {{-- Akses relasi barangGudang yang sudah di-eager load --}}
                    <td>{{ $item->barangGudang->nama_barang_gudang ?? 'N/A' }}</td>
                    <td>{{ $item->barangGudang->jenis_barang_gudang ?? 'N/A' }}</td>
                    <td>{{ $item->barangGudang->kategori_barang_gudang ?? 'N/A' }}</td>
                    <td>{{ $item->jumlah_barang }}</td>
                    <td>{{ $item->keterangan_gudang }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>