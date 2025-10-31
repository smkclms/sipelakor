<footer class="text-center mt-4 mb-4 py-3 bg-light border-top">
  <small class="text-muted">
    &copy; <?= date('Y') ?> <strong>Sipelakor</strong> — Sistem Pelaporan Keuangan Organisasi<br>
  Created by 
  <a href="https://www.profilsaya.my.id" target="_blank" class="footer-link">NidumzaN</a>
  </small>
</footer>

<!-- === JS LIBRARY === -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- === AUTO CSRF HANDLER (CodeIgniter 3 + AJAX) === -->
<script>
$(function() {
  function getCsrfFromCookie() {
    let name = 'csrf_cookie_name=';
    let decoded = decodeURIComponent(document.cookie);
    let ca = decoded.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i].trim();
      if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return '';
  }

  $.ajaxSetup({
    beforeSend: function(xhr, settings) {
      let token = getCsrfFromCookie();
      if (settings.type === 'POST') {
        if (typeof settings.data === 'string') {
          settings.data += '&csrf_test_name=' + token;
        } else if (typeof settings.data === 'object') {
          settings.data = settings.data || {};
          settings.data.csrf_test_name = token;
        }
      }
    }
  });
});
</script>

<!-- === RESPONSIVE LAYOUT HANDLER === -->
<style>
  html, body {
    width: 100%;
    overflow-x: hidden;
    background-color: #f8f9fa;
    font-family: "Poppins", "Segoe UI", sans-serif;
  }

  .main-content {
    transition: margin-left 0.3s ease, padding 0.3s ease;
    width: 100%;
    min-height: 100vh;
    box-sizing: border-box;
    background-color: #fff;
    margin-left: 230px;
  }

  /* === Saat sidebar collapse === */
  .sidebar.collapsed ~ .main-content {
    margin-left: 70px;
  }

  /* === Layout Default === */
  .layout-normal {
    padding: 30px 40px;
  }

  /* === Layout Full (otomatis untuk tabel/chart) === */
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

  /* === RESPONSIVE HANDLING === */
  @media (max-width: 992px) {
    .main-content {
      margin-left: 0 !important;
      padding: 20px 15px;
    }

    footer {
      font-size: 0.85rem;
    }

    .layout-normal {
      padding: 20px 15px;
    }

    .navbar,
    .sidebar {
      position: fixed;
      z-index: 1050;
    }
  }

  @media (max-width: 576px) {
    .main-content {
      padding: 15px 10px !important;
    }

    table {
      font-size: 0.875rem;
    }

    .btn, .form-control {
      font-size: 0.875rem;
    }

    h3, .h3 {
      font-size: 1.25rem;
    }

    footer small {
      font-size: 0.8rem;
    }
  }

  /* Smooth transitions */
  .layout-full, .layout-normal {
    transition: all 0.3s ease-in-out;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('mainContent');
    if (mainContent) {
      const hasTable = document.querySelector('.table, .table-responsive');
      const hasChart = document.querySelector('canvas, .chartjs-render-monitor');
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
