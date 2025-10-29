<!-- Pastikan Font Awesome tersedia -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* === SIDEBAR STYLE === */
.sidebar {
  width: 230px;
  min-height: 100vh;
  background: linear-gradient(180deg, #f8f9fa, #e9ecef);
  transition: all 0.3s ease;
  position: relative;
  box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
}
.sidebar.collapsed {
  width: 70px;
}
.sidebar .nav {
  list-style: none;
  padding-left: 0;
}
.sidebar .nav-link {
  display: flex;
  align-items: center;
  color: #333;
  padding: 10px 18px;
  text-decoration: none;
  transition: all 0.2s ease;
  border-radius: 8px;
  margin: 4px 8px;
}
.sidebar .nav-link:hover {
  background: #0d6efd;
  color: #fff;
}
.sidebar .nav-link i {
  width: 22px;
  text-align: center;
  margin-right: 10px;
}
.sidebar.collapsed .nav-link span {
  display: none;
}
.sidebar.collapsed .nav-link i {
  margin-right: 0;
}
.sidebar-toggle {
  position: absolute;
  bottom: 15px;
  width: 100%;
  text-align: center;
  cursor: pointer;
  font-size: 20px;
  color: #555;
  padding: 10px 0;
  border-top: 1px solid #ddd;
  background: #f8f9fa;
  transition: background 0.3s, transform 0.3s;
}
.sidebar-toggle:hover {
  background: #e2e6ea;
  color: #007bff;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    z-index: 1000;
    left: -230px;
  }
  .sidebar.active {
    left: 0;
  }
  .main-content {
    margin-left: 0 !important;
  }
  .sidebar-backdrop {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.3);
    z-index: 999;
  }
  .sidebar.active + .sidebar-backdrop {
    display: block;
  }
}
</style>

<!-- === SIDEBAR SEKOLAH === -->
<div class="sidebar" id="sidebar">
  <ul class="nav flex-column pt-3">
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('sekolah') ?>">
        <i class="fas fa-home"></i> <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('anggaran') ?>">
        <i class="fas fa-wallet"></i> <span>Anggaran Sekolah</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('pengeluaran') ?>">
        <i class="fas fa-file-invoice-dollar"></i> <span>Laporan Pengeluaran</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('laporan/sekolah') ?>">
        <i class="fas fa-chart-bar"></i> <span>Laporan Keuangan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('rekap') ?>">
        <i class="fas fa-clipboard-list"></i> <span>Rekap Pembelanjaan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('rekap/tambah') ?>">
        <i class="fas fa-plus-circle"></i> <span>Buat Pembelanjaan Baru</span>
      </a>
    </li>
  </ul>

  <!-- Tombol toggle bawah -->
  <div class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-angle-double-left"></i>
  </div>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- === Konten utama === -->
<div class="main-content col-md-10" id="mainContent" style="transition: margin-left 0.3s ease;">
