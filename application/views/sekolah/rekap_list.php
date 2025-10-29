<div class="container mt-4">
  <h4 class="mb-4">Rekap Pembelanjaan Sekolah</h4>

  <table class="table table-bordered table-hover table-sm">
    <thead class="thead-light">
      <tr>
        <th>No Invoice</th>
        <th>Tanggal</th>
        <th>Kegiatan</th>
        <th>Jenis Belanja</th>
        <th>Nilai Transaksi</th>
        <th>Platform</th>
        <th>Pembayaran</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($rekap as $r): ?>
        <tr>
          <td><?= $r->invoice_no ?></td>
          <td><?= $r->tanggal ?></td>
          <td><?= $r->kegiatan ?></td>
          <td><?= isset($r->jenis_belanja) ? $r->jenis_belanja : '-' ?></td>
          <td>Rp <?= number_format($r->nilai_transaksi,0,',','.') ?></td>
          <td><?= $r->platform ?></td>
          <td><?= $r->pembayaran ?></td>
          <td>
            <a href="<?= base_url('rekap/detail?invoice_no=' . urlencode($r->invoice_no)) ?>" class="btn btn-info btn-sm">Lihat Detail</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
