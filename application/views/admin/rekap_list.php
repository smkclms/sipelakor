<div class="container mt-4">
  <h4 class="mb-4">Rekap Pembelanjaan Semua Sekolah (Tahun <?= $tahun_aktif ?>)</h4>

  <form method="get" class="form-inline mb-3">
  <label class="mr-2">Pilih Tahun:</label>
  <select name="tahun_id" class="form-control mr-3" onchange="this.form.submit()">
    <?php foreach ($tahun_all as $t): ?>
      <option value="<?= $t->id ?>" <?= ($t->tahun == $tahun_aktif) ? 'selected' : '' ?>>
        <?= $t->tahun ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label class="mr-2">Sekolah:</label>
  <select name="sekolah_id" class="form-control mr-3" onchange="this.form.submit()">
    <option value="">-- Semua Sekolah --</option>
    <?php foreach ($sekolah_all as $s): ?>
      <option value="<?= $s->id ?>" <?= ($this->input->get('sekolah_id') == $s->id) ? 'selected' : '' ?>>
        <?= $s->nama ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label class="mr-2">Periode:</label>
  <select name="bulan" class="form-control mr-3" onchange="this.form.submit()">
    <option value="">-- Semua Bulan --</option>
    <?php
      $bulan_list = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
      ];
      $bulan_terpilih = $this->input->get('bulan');
      foreach ($bulan_list as $num => $nama):
    ?>
      <option value="<?= $num ?>" <?= ($bulan_terpilih == $num) ? 'selected' : '' ?>><?= $nama ?></option>
    <?php endforeach; ?>
  </select>

  <noscript><button type="submit" class="btn btn-primary btn-sm">Filter</button></noscript>
</form>

  <div class="mb-3">
  <a href="<?= base_url('rekap/export_excel?tahun_id=' . $this->input->get('tahun_id') . '&bulan=' . $this->input->get('bulan') . '&sekolah_id=' . $this->input->get('sekolah_id')) ?>" 
     class="btn btn-success btn-sm">
    <i class="fa fa-file-excel"></i> Export Excel
  </a>

  <a href="<?= base_url('rekap/export_pdf?tahun_id=' . $this->input->get('tahun_id') . '&bulan=' . $this->input->get('bulan') . '&sekolah_id=' . $this->input->get('sekolah_id')) ?>" 
     class="btn btn-danger btn-sm">
    <i class="fa fa-file-pdf"></i> Export PDF
  </a>
</div>

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
<style>
  /* === BASE MAIN CONTENT === */
.main-content {
  transition: margin-left 0.3s ease, padding 0.3s ease;
  width: 100%;
  margin-left: 230px;
  min-height: 100vh;
  box-sizing: border-box;
  background-color: #fff;
}

/* Saat sidebar collapse */
.sidebar.collapsed ~ .main-content {
  margin-left: 70px;
}

/* === MODE NORMAL === */
.layout-normal {
  padding: 30px 40px;
}

/* === MODE FULL WIDTH === */
.layout-full {
  padding: 15px 10px;
  max-width: 100%;
}

.layout-full .table-responsive {
  width: 100%;
  overflow-x: auto;
}

.layout-full .container,
.layout-full .container-fluid {
  max-width: 100% !important;
  padding: 0 !important;
}

html, body {
  width: 100%;
  overflow-x: hidden;
}

</style>
