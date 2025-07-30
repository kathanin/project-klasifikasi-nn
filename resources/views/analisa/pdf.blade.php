<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisa DBR</title>
    <style>
        body { font-family: sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #e2f0d9; }
    </style>
</head>
<body>
    <h3>Hasil Analisa DBR</h3>
    <h5>Nama Nasabah : {{$nama}}</h5>
    <h5>NIK : {{$nik}}</h5>
    <table>
        <tr>
            <th>Komponen</th>
            <th>Nilai</th>
        </tr>
        <tr>
            <td>Total Penghasilan</td>
            <td>Rp. {{ number_format($total_penghasilan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Angsuran Pengajuan</td>
            <td>Rp. {{ number_format($angsuran_pengajuan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Angsuran Lain</td>
            <td>Rp. {{ number_format($angsuran_lain, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Angsuran</td>
            <td>Rp. {{ number_format($total_angsuran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>DBR</td>
            <td>{{ $dbr }}%</td>
        </tr>
        <tr>
            <td>Hasil Rekomendasi</td>
            <td>{{ $hasil_rekomendasi }}</td>
        </tr>
    </table>
</body>
</html>
