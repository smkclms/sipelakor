<!-- Pastikan Font Awesome tersedia -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* === SIDEBAR FIXED STYLE === */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 230px;
  background: linear-gradient(180deg, #f8f9fa, #e9ecef);
  box-shadow: 2px 0 6px rgba(0,0,0,0.05);
  transition: width 0.3s ease;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  z-index: 1000;
}

.sidebar.collapsed {
  width: 70px;
}

/* NAV LINK STYLE */
.sidebar .nav {
  list-style: none;
  padding-left: 0;
  margin: 0;
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
  font-weight: 500;
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

/* TOGGLE BUTTON BAWAH */
.sidebar-toggle {
  width: 100%;
  text-align: center;
  cursor: pointer;
  font-size: 20px;
  color: #555;
  padding: 12px 0;
  border-top: 1px solid #ddd;
  background: #f8f9fa;
  transition: background 0.3s, color 0.3s;
}
.sidebar-toggle:hover {
  background: #e2e6ea;
  color: #007bff;
}

/* === KONTEN UTAMA === */
.main-content {
  margin-left: 230px;
  padding: 20px;
  transition: margin-left 0.3s ease;
}
.sidebar.collapsed ~ .main-content {
  margin-left: 70px;
}

/* === RESPONSIVE (mobile) === */
@media (max-width: 768px) {
  .sidebar {
    left: -230px;
    transition: left 0.3s ease;
  }
  .sidebar.active {
    left: 0;
  }
  .main-content {
    margin-left: 0 !important;
  }
  .sidebar.collapsed ~ .main-content {
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
/* === LOGO HEADER === */
.sidebar-header {
  background: linear-gradient(180deg, #f8f9fa, #eef1f4);
  border-radius: 8px;
  text-align: center;
  margin: 0 10px 10px 10px;
  padding: 10px 0;
  transition: opacity 0.3s ease;
}

.sidebar-header a {
  text-decoration: none;
  color: inherit;
}

.sidebar-header:hover h4 {
  color: #0056b3;
  transition: color 0.2s ease;
}

.sidebar-header i {
  color: #0d6efd;
}

.sidebar.collapsed .sidebar-header small {
  display: none;
}

.sidebar.collapsed .sidebar-header h4 {
  font-size: 1.6rem;
}
</style>

<!-- === SIDEBAR SEKOLAH === -->
<div class="sidebar" id="sidebar">
  <div>
    <!-- === LOGO HEADER DI SIDEBAR === -->
<div class="sidebar-header text-center py-3 mb-2 border-bottom">
  <a href="<?= base_url('sekolah') ?>" class="text-decoration-none d-block">
    <h4 class="fw-bold mb-0" style="color:#0d6efd;">
      <i class="fas fa-coins me-2"></i> SIPELAKOR
    </h4>
    <small class="text-muted" style="font-size:11px;">Sistem Pelaporan Keuangan Organisasi</small>
  </a>
</div>
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
  </div>

  <!-- Tombol toggle bawah -->
  <div class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-angle-double-left"></i>
  </div>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- === Konten utama === -->
<div class="main-content" id="mainContent">
<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  const backdrop = document.getElementById('sidebarBackdrop');

  toggleBtn.addEventListener('click', function() {
    const icon = toggleBtn.querySelector('i');

    // Desktop
    if (window.innerWidth > 768) {
      sidebar.classList.toggle('collapsed');
      if (sidebar.classList.contains('collapsed')) {
        icon.classList.remove('fa-angle-double-left');
        icon.classList.add('fa-angle-double-right');
      } else {
        icon.classList.remove('fa-angle-double-right');
        icon.classList.add('fa-angle-double-left');
      }
    } else {
      // Mobile slide
      sidebar.classList.toggle('active');
    }
  });

  // Klik backdrop untuk menutup sidebar di HP
  backdrop.addEventListener('click', function() {
    sidebar.classList.remove('active');
  });
});
</script>
