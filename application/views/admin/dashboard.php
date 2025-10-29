<div class="container mt-5">
  <div class="text-center mb-4">
    <h3 class="fw-bold">Selamat Datang, <?= $nama ?></h3>
    <p class="text-muted">Anda login sebagai <strong>Admin Dinas Pendidikan</strong></p>
  </div>

  <div class="row g-4 mb-4">
    <!-- Card SMK -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100 text-center">
        <div class="card-body">
          <h5 class="card-title text-primary fw-bold">🎓 SMK</h5>
          <p class="mb-1">Jumlah Sekolah: <strong><?= $jumlah_smk ?></strong></p>
          <p class="text-muted mb-0">Pagu Anggaran: <strong>Rp <?= number_format($pagu_smk, 0, ',', '.') ?></strong></p>
        </div>
      </div>
    </div>

    <!-- Card SMA -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100 text-center">
        <div class="card-body">
          <h5 class="card-title text-success fw-bold">🏫 SMA</h5>
          <p class="mb-1">Jumlah Sekolah: <strong><?= $jumlah_sma ?></strong></p>
          <p class="text-muted mb-0">Pagu Anggaran: <strong>Rp <?= number_format($pagu_sma, 0, ',', '.') ?></strong></p>
        </div>
      </div>
    </div>

    <!-- Card SLB -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100 text-center">
        <div class="card-body">
          <h5 class="card-title text-warning fw-bold">🤝 SLB</h5>
          <p class="mb-1">Jumlah Sekolah: <strong><?= $jumlah_slb ?></strong></p>
          <p class="text-muted mb-0">Pagu Anggaran: <strong>Rp <?= number_format($pagu_slb, 0, ',', '.') ?></strong></p>
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
.list-group-item {
  font-size: 15px;
}
</style>
