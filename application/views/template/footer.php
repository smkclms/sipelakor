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
<script>
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
</script>


</body>
</html>
