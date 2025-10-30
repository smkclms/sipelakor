<footer class="text-center mt-4 mb-4">
  <small>&copy; <?= date('Y') ?> Sipelakor - Sistem Pelaporan Keuangan</small>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
/* === AUTO-FIX CSRF UNTUK CODEIGNITER 3 + AJAX === */
$(function(){
  // ambil token dari cookie
  function getCsrfFromCookie() {
    let name = 'csrf_cookie_name=';
    let decoded = decodeURIComponent(document.cookie);
    let ca = decoded.split(';');
    for(let i=0; i<ca.length; i++) {
      let c = ca[i].trim();
      if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return '';
  }

  // tambahkan token di setiap request
  $.ajaxSetup({
    beforeSend: function(xhr, settings) {
      let token = getCsrfFromCookie();
      if (settings.type === 'POST') {
        if (typeof settings.data === 'string') {
          // jika data sudah ada, tambahkan token di belakang
          settings.data += '&csrf_test_name=' + token;
        } else if (typeof settings.data === 'object') {
          // jika data berupa object, tambahkan properti
          settings.data = settings.data || {};
          settings.data.csrf_test_name = token;
        }
      }
    }
  });
});
</script>
<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

    toggleBtn.addEventListener('click', function() {
      // Mode desktop
      if (window.innerWidth > 768) {
        sidebar.classList.toggle('collapsed');
        const icon = toggleBtn.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
          icon.classList.remove('fa-angle-double-left');
          icon.classList.add('fa-angle-double-right');
        } else {
          icon.classList.remove('fa-angle-double-right');
          icon.classList.add('fa-angle-double-left');
        }
      } else {
        // Mode mobile
        sidebar.classList.toggle('active');
      }
    });

    backdrop.addEventListener('click', function() {
      sidebar.classList.remove('active');
    });
  });
</script> -->
<!-- === AUTO FULL-WIDTH LAYOUT HANDLER === -->
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

  /* === MODE NORMAL (default) === */
  .layout-normal {
    padding: 30px 40px;
  }

  /* === MODE FULL WIDTH (otomatis aktif untuk halaman dengan tabel/chart) === */
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

  /* Animasi halus saat transisi */
  .layout-full, .layout-normal {
    transition: all 0.3s ease-in-out;
  }

  @media (max-width: 768px) {
    .main-content {
      margin-left: 0 !important;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('mainContent');

    if (mainContent) {
      // deteksi kalau halaman punya tabel besar atau grafik
      const hasTable = document.querySelector('.table, .table-responsive');
      const hasChart = document.querySelector('canvas, .chartjs-render-monitor');

      // otomatis tentukan layout
      if (hasTable || hasChart) {
        mainContent.classList.add('layout-full');
      } else {
        mainContent.classList.add('layout-normal');
      }
    }
  });
</script>
</body>
</html>
