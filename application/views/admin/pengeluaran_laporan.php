<div class="container mt-4">
  <h4 class="mb-4 text-primary">Laporan Pengeluaran Semua Sekolah Tahun <?= $tahun ?></h4>

  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>No</th>
        <th>Nama Sekolah</th>
        <th>Sumber Anggaran</th>
        <th>Jenis Belanja</th>
        <th>Kodering</th>
        <th>Uraian</th>
        <th>Jumlah</th>
        <th>Tanggal</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($pengeluaran)): ?>
        <tr><td colspan="9" class="text-center text-muted">Belum ada data pengeluaran</td></tr>
      <?php else: ?>
        <?php $no=1; foreach($pengeluaran as $p): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $p->nama_sekolah ?></td>
            <td><?= $p->sumber_anggaran ?></td>
            <td><?= $p->nama_jenis_belanja ?></td>
            <td><?= $p->kode ?> - <?= $p->nama_kodering ?></td>
            <td><?= $p->uraian ?></td>
            <td>Rp <?= number_format($p->jumlah, 0, ',', '.') ?></td>
            <td><?= $p->tanggal ?></td>
            <td><?= $p->status ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
