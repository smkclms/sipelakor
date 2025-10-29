<div class="container mt-4">
  <h3 class="fw-bold">Selamat Datang, <?= $this->session->userdata('nama'); ?></h3>
  <p class="text-muted">Berikut ringkasan keuangan sekolah Anda</p>

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

  <!-- 3 CARD DETAIL -->
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

  <!-- GRAFIK PENGELUARAN -->
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
      borderWidth: 1
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } },
    plugins: { legend: { display: false } }
  }
});
</script>
