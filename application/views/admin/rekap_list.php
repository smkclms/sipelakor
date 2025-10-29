<div class="container mt-4">
  <h4 class="mb-4">Rekap Pembelanjaan Semua Sekolah (Tahun <?= $tahun_aktif ?>)</h4>

  <form method="get" class="form-inline mb-3">
    <label class="mr-2">Pilih Tahun:</label>
    <select name="tahun_id" class="form-control mr-2" onchange="this.form.submit()">
      <?php foreach ($tahun_all as $t): ?>
        <option value="<?= $t->id ?>" <?= ($t->tahun == $tahun_aktif) ? 'selected' : '' ?>>
          <?= $t->tahun ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <table class="table table-bordered table-hover table-sm">
    <thead class="thead-light">
      <tr>
        <th>No Invoice</th>
        <th>Nama Sekolah</th>
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
      <?php if (!empty($rekap)): ?>
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
            <td>
              <a href="<?= base_url('rekap/detail?invoice_no=' . urlencode($r->invoice_no)) ?>" class="btn btn-info btn-sm">Lihat Detail</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9" class="text-center text-muted">Tidak ada data untuk tahun ini.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="alert alert-info mt-3">
    <strong>Total Penggunaan Tahun <?= $tahun_aktif ?>:</strong> 
    Rp <?= number_format($total_penggunaan, 0, ',', '.') ?>
  </div>
</div>
