<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekap Pembelanjaan <?= $tahun_aktif ?></title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    th { background: #e9ecef; }
  </style>
</head>
<body>
  <h3 style="text-align:center;">Rekap Pembelanjaan Semua Sekolah<br>Tahun <?= $tahun_aktif ?></h3>
  <table>
    <thead>
      <tr>
        <th>No Invoice</th>
        <th>Nama Sekolah</th>
        <th>Tanggal</th>
        <th>Kegiatan</th>
        <th>Jenis Belanja</th>
        <th>Nilai Transaksi</th>
        <th>Platform</th>
        <th>Pembayaran</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rekap as $r): ?>
      <tr>
        <td><?= $r->invoice_no ?></td>
        <td><?= $r->nama_sekolah ?></td>
        <td><?= $r->tanggal ?></td>
        <td><?= $r->kegiatan ?></td>
        <td><?= $r->jenis_belanja ?></td>
        <td>Rp <?= number_format($r->nilai_transaksi, 0, ',', '.') ?></td>
        <td><?= $r->platform ?></td>
        <td><?= $r->pembayaran ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
