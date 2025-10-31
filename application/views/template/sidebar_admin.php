<!-- Pastikan Font Awesome sudah aktif -->
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
  transition: all 0.3s ease;
  box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* agar toggle selalu di bawah */
  z-index: 1000;
}

.sidebar.collapsed {
  width: 70px;
}

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
  background: #007bff;
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

/* === TOGGLE BUTTON === */
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

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .sidebar {
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

/* === RESPONSIVE KHUSUS MOBILE & TABLET === */

/* Tablet dan mobile (≤992px) */
@media (max-width: 992px) {
  /* Sidebar di luar layar secara default */
  .sidebar {
    left: -230px;
    height: 100%;
    transition: left 0.3s ease-in-out;
    z-index: 1050;
  }

  /* Saat aktif, sidebar muncul slide-in */
  .sidebar.active {
    left: 0;
    box-shadow: 3px 0 10px rgba(0,0,0,0.15);
  }

  /* Backdrop muncul di belakang sidebar */
  .sidebar-backdrop {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.35);
    z-index: 1040;
    transition: opacity 0.3s ease-in-out;
  }

  .sidebar.active + .sidebar-backdrop {
    display: block;
    opacity: 1;
  }

  /* Konten utama tidak terdorong di mobile */
  .main-content {
    margin-left: 0 !important;
    transition: margin 0.3s ease;
    padding: 15px;
  }

  /* Sidebar header lebih kecil di mobile */
  .sidebar-header h4 {
    font-size: 1.2rem;
  }

  .sidebar-header small {
    font-size: 0.75rem;
  }

  /* Link di sidebar lebih rapat */
  .sidebar .nav-link {
    padding: 8px 14px;
    font-size: 0.9rem;
  }

  /* Ikon sedikit lebih kecil */
  .sidebar .nav-link i {
    width: 20px;
    margin-right: 8px;
    font-size: 1rem;
  }

  /* Tombol toggle lebih besar dan clickable */
  .sidebar-toggle {
    padding: 14px 0;
    font-size: 1.2rem;
  }

  /* Hilangkan scroll horizontal */
  body, html {
    overflow-x: hidden;
  }
}

/* Mobile kecil (≤576px) */
@media (max-width: 576px) {
  .sidebar {
    width: 210px;
  }

  .sidebar .nav-link {
    font-size: 0.85rem;
    padding: 8px 12px;
  }

  .sidebar-header h4 {
    font-size: 1rem;
  }

  .sidebar-header small {
    font-size: 0.7rem;
  }

  .sidebar-toggle {
    font-size: 1.1rem;
    padding: 12px 0;
  }
}

</style>

<!-- === SIDEBAR === -->
<div class="sidebar" id="sidebar">
  <div>
    <!-- === LOGO HEADER DI SIDEBAR === -->
<div class="sidebar-header text-center py-3 mb-2 border-bottom">
  <a href="<?= base_url('admin') ?>" class="text-decoration-none d-block">
    <h4 class="fw-bold mb-0" style="color:#0d6efd;">
      <i class="fas fa-coins me-2"></i> SIPELAKOR
    </h4>
    <small class="text-muted" style="font-size:11px;">Sistem Pelaporan Keuangan Organisasi</small>
  </a>
</div>
    <ul class="nav flex-column pt-3">
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin') ?>">
          <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('user_management'); ?>">
          <i class="fas fa-users"></i> <span>Manajemen Pengguna</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('datasekolah') ?>">
          <i class="fas fa-school"></i> <span>Data Sekolah</span>
        </a>
      </li>
      <li class="nav-item">
    <a class="nav-link" href="<?= base_url('kategori_belanja') ?>">
        <i class="fas fa-tags"></i>
        <span>Kategori Belanja</span>
    </a>
</li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('pengeluaran_admin') ?>">
          <i class="fas fa-check-circle"></i> <span>Verifikasi Pengeluaran</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('kodering') ?>">
          <i class="fas fa-code"></i> <span>Pengelolaan Kodering</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('laporan') ?>">
          <i class="fas fa-chart-line"></i> <span>Laporan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('rekap/admin') ?>">
          <i class="fas fa-file-invoice-dollar"></i> <span>Rekap Pembelanjaan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('pengeluaran_admin/laporan') ?>">
          <i class="fas fa-money-bill"></i> <span>Laporan Pengeluaran</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= site_url('backup'); ?>">
          <i class="fa fa-download"></i> <span>Backup Data</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- === TOGGLE BUTTON PINDAH KE BAWAH === -->
  <div class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-angle-double-left"></i>
  </div>
</div>

<!-- BACKDROP UNTUK MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- === KONTEN UTAMA === -->
<div class="main-content" id="mainContent" data-layout="auto">

  <!-- Konten halaman dimuat di sini -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');
    const mainContent = document.getElementById('mainContent'); // pastikan ini ada

    toggleBtn.addEventListener('click', function() {
      const icon = toggleBtn.querySelector('i');
      
      // Mode desktop
      if (window.innerWidth > 768) {
        sidebar.classList.toggle('collapsed');

        // Ubah icon
        if (sidebar.classList.contains('collapsed')) {
          icon.classList.remove('fa-angle-double-left');
          icon.classList.add('fa-angle-double-right');
          mainContent.style.marginLeft = "70px";
        } else {
          icon.classList.remove('fa-angle-double-right');
          icon.classList.add('fa-angle-double-left');
          mainContent.style.marginLeft = "230px";
        }
      } else {
        // Mode mobile (slide in/out)
        sidebar.classList.toggle('active');
      }
    });

    // Klik backdrop di mobile → tutup sidebar
    backdrop.addEventListener('click', function() {
      sidebar.classList.remove('active');
    });
  });
</script>

