<div class="content-wrapper mt-4 px-4">
  <div class="text-center mb-4">
    <h3 class="fw-bold">Selamat Datang, <?= $nama ?></h3>
    <p class="text-muted">Anda login sebagai <strong>Admin Dinas Pendidikan</strong></p>
  </div>

  <!-- 3 CARD UTAMA -->
  <div class="row g-4 mb-4">
    <!-- Card SMK -->
    <div class="col-md-4 col-sm-6">
      <div class="card border-0 shadow-sm h-100 text-center card-main">
        <div class="card-body">
          <h5 class="card-title text-primary fw-bold">🎓 SMK</h5>
          <p class="mb-1">Jumlah Sekolah: <strong><?= $jumlah_smk ?></strong></p>
          <p class="text-muted mb-0">Pagu Anggaran: <strong>Rp <?= number_format($pagu_smk, 0, ',', '.') ?></strong></p>
        </div>
      </div>
    </div>

    <!-- Card SMA -->
    <div class="col-md-4 col-sm-6">
      <div class="card border-0 shadow-sm h-100 text-center card-main">
        <div class="card-body">
          <h5 class="card-title text-success fw-bold">🏫 SMA</h5>
          <p class="mb-1">Jumlah Sekolah: <strong><?= $jumlah_sma ?></strong></p>
          <p class="text-muted mb-0">Pagu Anggaran: <strong>Rp <?= number_format($pagu_sma, 0, ',', '.') ?></strong></p>
        </div>
      </div>
    </div>

    <!-- Card SLB -->
    <div class="col-md-4 col-sm-6">
      <div class="card border-0 shadow-sm h-100 text-center card-main">
        <div class="card-body">
          <h5 class="card-title text-warning fw-bold">🤝 SLB</h5>
          <p class="mb-1">Jumlah Sekolah: <strong><?= $jumlah_slb ?></strong></p>
          <p class="text-muted mb-0">Pagu Anggaran: <strong>Rp <?= number_format($pagu_slb, 0, ',', '.') ?></strong></p>
        </div>
      </div>
    </div>
  </div>

  <!-- ======================================================= -->
<!--  CARD PER SUMBER ANGGARAN PER JENJANG -->
<!-- ======================================================= -->
<?php if (!empty($jenjang_summary)): ?>
  <h5 class="fw-bold mt-4 mb-3 text-primary">📘 Rincian Anggaran per Jenjang & Sumber</h5>

  <?php foreach ($jenjang_summary as $j): ?>
    <div class="mb-4 p-3 bg-light rounded shadow-sm">
      <h5 class="fw-bold text-dark mb-3">
        <i class="bi bi-building"></i> <?= $j['jenjang'] ?>
      </h5>

      <?php if (!empty($j['detail'])): ?>
        <div class="card-grid">
          <?php foreach ($j['detail'] as $s): ?>
            <div class="card shadow-sm border-0 h-100">
              <div class="card-body">
                <h6 class="fw-bold text-primary mb-2"><?= strtoupper($s['nama']) ?></h6>
                <p class="text-muted mb-1">Sekolah: <?= $s['jumlah_sekolah'] ?></p>
                <p class="text-muted mb-1">Pagu: <strong>Rp <?= number_format($s['pagu'], 0, ',', '.') ?></strong></p>
                <p class="text-muted mb-1">Pengeluaran: <strong>Rp <?= number_format($s['pengeluaran'], 0, ',', '.') ?></strong></p>
                <p class="text-muted mb-2">Sisa: <strong>Rp <?= number_format($s['sisa'], 0, ',', '.') ?></strong></p>

                <div class="text-small text-muted mb-1">
                  Penggunaan: <?= $s['persen'] ?>%
                </div>
                <div class="progress mb-1" style="height: 6px;">
                  <div class="progress-bar 
                    <?= $s['persen'] >= 80 ? 'bg-danger' : ($s['persen'] >= 50 ? 'bg-warning' : 'bg-success') ?>"
                    style="width: <?= $s['persen'] ?>%">
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-muted">Belum ada data anggaran untuk jenjang ini.</p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>


<!-- ======================================================= -->
<!--  RINCIAN PENGELUARAN BERDASARKAN SUMBER (GLOBAL) -->
<!-- ======================================================= -->
<div class="mt-5">
  <h5 class="fw-bold mb-3 text-secondary">📊 Pengeluaran Berdasarkan Sumber Anggaran</h5>
  <div class="card-grid">
    <?php 
// Hitung total semua pengeluaran secara manual (PHP 5.3-safe)
$total_pengeluaran = 0;
foreach ($by_sumber as $temp) {
    if (isset($temp->total)) {
        $total_pengeluaran += $temp->total;
    }
}
?>

<?php foreach ($by_sumber as $r): 
  $persen = ($total_pengeluaran > 0 && isset($r->total)) ? round(($r->total / $total_pengeluaran) * 100) : 0;
  $warna = $persen >= 80 ? 'bg-danger' : ($persen >= 50 ? 'bg-warning' : 'bg-success');
?>

      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h6 class="fw-bold text-primary"><?= $r->sumber ?></h6>
          <h5>Rp <?= number_format($r->total, 0, ',', '.') ?></h5>
          <div class="text-small text-muted mb-1">Proporsi: <?= $persen ?>%</div>
          <div class="progress">
            <div class="progress-bar <?= $warna ?>" style="width: <?= $persen ?>%"></div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<!-- ======================================================= -->
<!--  📘 DETAIL RINCIAN PENGELUARAN -->
<!-- ======================================================= -->
<h5 class="fw-bold mt-5 mb-3 text-primary">📘 Detail Rincian Pengeluaran</h5>

<div class="row g-3 mb-4">
  <!-- Berdasarkan Sumber Anggaran -->
  <div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-bold text-primary mb-3">🔹 Berdasarkan Sumber Anggaran</h6>
        <ul class="list-group list-group-flush">
          <?php if (!empty($by_sumber)): ?>
            <?php foreach ($by_sumber as $r): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $r->sumber ?>
                <span>Rp <?= number_format($r->total, 0, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item text-muted text-center">Belum ada data</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Berdasarkan Kategori Belanja -->
  <div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-bold text-success mb-3">📁 Berdasarkan Kategori Belanja</h6>
        <ul class="list-group list-group-flush">
          <?php if (!empty($by_kategori)): ?>
            <?php foreach ($by_kategori as $r): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $r->kategori ?>
                <span>Rp <?= number_format($r->total, 0, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item text-muted text-center">Belum ada data</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Berdasarkan Kodering -->
  <div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-bold text-warning mb-3">⚙️ Berdasarkan Kodering</h6>
        <ul class="list-group list-group-flush">
          <?php if (!empty($by_kodering)): ?>
            <?php foreach ($by_kodering as $r): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $r->kodering ?>
                <span>Rp <?= number_format($r->total, 0, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item text-muted text-center">Belum ada data</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>


  <hr>

  <h5 class="fw-bold mb-3">Menu Cepat</h5>
  <div class="list-group shadow-sm">
    <a href="<?= base_url('pengeluaran/daftar') ?>" class="list-group-item list-group-item-action">
      🧾 Verifikasi Laporan Pengeluaran Sekolah
    </a>
    <a href="<?= base_url('kodering') ?>" class="list-group-item list-group-item-action">
      ⚙️ Kelola Kodering Belanja
    </a>
    <a href="<?= base_url('laporan') ?>" class="list-group-item list-group-item-action">
      📊 Lihat Laporan Keuangan
    </a>
  </div>
</div>

<style>
.card {
  border-radius: 15px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Card utama di bagian atas */
.card-main {
  min-height: 140px;
}

/* Card per sumber — dinamis tinggi dan lebar */
.card-sumber {
  min-height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* Untuk grid dinamis (otomatis menyesuaikan jumlah card) */
@media (max-width: 768px) {
  .col-md-6, .col-lg-4, .col-xl-3 {
    flex: 0 0 100%;
    max-width: 100%;
  }
}

.list-group-item {
  font-size: 15px;
}
.progress {
  height: 8px;
  border-radius: 10px;
}
.text-small {
  font-size: 0.85rem;
}
/* Grid layout adaptif */
.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 1rem;
}

/* Card styling umum */
.card {
  border-radius: 15px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}

.list-group-item {
  font-size: 15px;
}
.progress {
  height: 8px;
  border-radius: 10px;
}
.text-small {
  font-size: 0.85rem;
}

/* Responsive padding */
@media (max-width: 576px) {
  .card-grid {
    grid-template-columns: 1fr;
  }
}
/* === FULL WIDTH CONTENT WRAPPER === */
.content-wrapper {
  width: 100%;
  max-width: 100%;
  margin: 0;
  padding: 0; /* beri sedikit ruang biar tidak mentok layar */
  box-sizing: border-box;
}

/* Hilangkan batas max-width dari Bootstrap container */
.container,
.container-fluid {
  max-width: 100% !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}


</style>
