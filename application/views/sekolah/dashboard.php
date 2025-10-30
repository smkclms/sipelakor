<style>
  /* 🎨 General card styling */
  .card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
  }

  /* 🏷️ Section header */
  .section-header {
    display: flex;
    align-items: center;
    margin-top: 2rem;
    margin-bottom: 1rem;
    border-left: 5px solid #0d6efd;
    padding-left: 10px;
  }
  .section-header h5 {
    margin: 0;
    font-weight: 700;
    color: #0d6efd;
  }

  /* 💳 Group box per sumber */
  .source-group {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 10px 15px 15px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }

  /* 📊 Progress bar */
  .progress {
    height: 8px;
    border-radius: 10px;
  }
  .progress-bar {
    transition: width 0.4s ease;
  }

  .text-small {
    font-size: 0.85rem;
  }
</style>

<div class="container mt-4">
  <h3 class="fw-bold">Selamat Datang, <?= $this->session->userdata('nama'); ?></h3>
  <p class="text-muted">Total Pengeluaran Dari semua Jenis Sumber Anggaran</p>

  <!-- 3 CARD UTAMA -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100 text-center">
        <div class="card-body">
          <h6 class="text-primary fw-bold">💰 Total Pagu Anggaran</h6>
          <h4>Rp <?= number_format($total_pagu, 0, ',', '.') ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100 text-center">
        <div class="card-body">
          <h6 class="text-danger fw-bold">📤 Total Pengeluaran</h6>
          <h4>Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100 text-center">
        <div class="card-body">
          <h6 class="text-success fw-bold">💵 Sisa Anggaran</h6>
          <h4>Rp <?= number_format($sisa_anggaran, 0, ',', '.') ?></h4>
        </div>
      </div>
    </div>
  </div>

  <!-- ======================================================= -->
  <!--  CARD PER SUMBER ANGGARAN (DINAMIS & TERPISAH RAPIH) -->
  <!-- ======================================================= -->
  <?php if (!empty($sumber_summary)): ?>
    <div class="section-header">
      <h5>📘Pengeluaran Berdasarkan Sumber Anggaran</h5>
    </div>

    <?php foreach ($sumber_summary as $s): ?>
      <?php
        $total = $s['pagu'] > 0 ? round(($s['pengeluaran'] / $s['pagu']) * 100) : 0;
        $warna = $total >= 80 ? 'bg-danger' : ($total >= 50 ? 'bg-warning' : 'bg-success');
      ?>
      <div class="source-group">
        <h6 class="fw-bold mb-3 text-primary">
          <i class="bi bi-bank2"></i> <?= strtoupper($s['nama']) ?>
        </h6>

        <div class="row g-3 mb-2">
          <!-- Card Pagu -->
          <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center h-100">
              <div class="card-body">
                <h6 class="fw-bold text-primary">💰 Pagu <?= $s['nama'] ?></h6>
                <h5>Rp <?= number_format($s['pagu'], 0, ',', '.') ?></h5>
              </div>
            </div>
          </div>

          <!-- Card Pengeluaran -->
          <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center h-100">
              <div class="card-body">
                <h6 class="fw-bold text-danger">📤 Pengeluaran <?= $s['nama'] ?></h6>
                <h5>Rp <?= number_format($s['pengeluaran'], 0, ',', '.') ?></h5>
              </div>
            </div>
          </div>

          <!-- Card Sisa -->
          <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center h-100">
              <div class="card-body">
                <h6 class="fw-bold text-success">💵 Sisa Anggaran <?= $s['nama'] ?></h6>
                <h5>Rp <?= number_format($s['sisa'], 0, ',', '.') ?></h5>
              </div>
            </div>
          </div>
        </div>

        <!-- Progress bar -->
        <div class="text-small text-muted mb-1">
          Penggunaan: <?= $total ?>%
        </div>
        <div class="progress mb-2">
          <div class="progress-bar <?= $warna ?>" role="progressbar" style="width: <?= $total ?>%"></div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- ======================================================= -->
  <!--  3 CARD DETAIL -->
  <!-- ======================================================= -->
   <h5>📘Detail Rincian Pengeluaran</h5>
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="fw-bold text-primary">🔹 Berdasarkan Sumber Anggaran</h6>
          <ul class="list-group list-group-flush">
            <?php foreach ($by_sumber as $r): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $r->sumber ?>
                <span>Rp <?= number_format($r->total, 0, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="fw-bold text-success">📁 Berdasarkan Kategori Belanja</h6>
          <ul class="list-group list-group-flush">
            <?php foreach ($by_kategori as $r): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $r->kategori ?>
                <span>Rp <?= number_format($r->total, 0, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="fw-bold text-warning">⚙️ Berdasarkan Kodering</h6>
          <ul class="list-group list-group-flush">
            <?php foreach ($by_kodering as $r): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $r->kodering ?>
                <span>Rp <?= number_format($r->total, 0, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- ======================================================= -->
  <!--  GRAFIK PENGELUARAN -->
  <!-- ======================================================= -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <h6 class="fw-bold text-secondary">📈 Grafik Pengeluaran Tiap Bulan</h6>
      <canvas id="chartPengeluaran" height="100"></canvas>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPengeluaran');
const chartData = <?= json_encode($chart_data) ?>;
const labels = chartData.map(e => 'Bulan ' + e.bulan);
const values = chartData.map(e => e.total);

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'Total Pengeluaran',
      data: values,
      backgroundColor: 'rgba(13,110,253,0.7)',
      borderRadius: 6
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } },
    plugins: { legend: { display: false } }
  }
});
</script>
