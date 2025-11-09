<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />

  <!-- Open Graph -->
  <meta property="og:title" content="Sipelakor — Sistem Pelaporan Keuangan Sekolah" />
  <meta property="og:description" content="Pantau dan kelola laporan keuangan sekolah dengan mudah, cepat, dan transparan." />
  <meta property="og:image" content="https://devkeu.smkn1-cilimus.sch.id/assets/img/sipelakor.png" />
  <meta property="og:url" content="https://devkeu.smkn1-cilimus.sch.id/" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Sipelakor" />

  <title>Login Sipelakor</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #e0f2ff, #f9f9f9);
      min-height: 100vh;
      font-family: "Poppins", sans-serif;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      min-height: 100vh;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-header {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: #fff;
      text-align: center;
      padding: 1.5rem;
    }

    .login-header h4 { margin: 0; font-weight: 600; }

    .login-body { padding: 1.5rem; }

    .form-control { border-radius: 8px; }

    .btn-primary {
      background: linear-gradient(135deg, #007bff, #0069d9);
      border: none;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #0062cc, #004ea1);
      transform: translateY(-2px);
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 70%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #aaa;
    }

    .toggle-password:hover { color: #007bff; }

    .footer-text {
      text-align: center;
      color: #777;
      font-size: 0.9rem;
      margin-top: 1.5rem;
    }

    @media (max-width: 576px) {
      .login-card { margin: 1rem; }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h4><i class="fa fa-coins mr-2"></i> Login Sipelakor</h4>
        <small>Sistem Pelaporan Keuangan Sekolah</small>
      </div>

      <div class="login-body">
        <?php if($this->session->flashdata('error')): ?>
          <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('auth/login') ?>" method="post">
          <input type="hidden" 
                name="<?= $this->security->get_csrf_token_name(); ?>" 
                value="<?= $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label>No Kontrol / ID Sekolah</label>
            <input type="text" name="no_kontrol" class="form-control" required>
          </div>

          <div class="form-group position-relative">
            <label>Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
          </div>

          <div class="form-group">
            <label for="tahun">Tahun Anggaran</label>
            <select name="tahun_id" class="form-control" required>
              <?php foreach ($tahun as $t): ?>
                <option value="<?= $t->id ?>" <?= $t->aktif ? 'selected' : '' ?>>
                  <?= $t->tahun ?><?= $t->aktif ? ' (aktif)' : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary btn-block mt-3">Masuk</button>
        </form>
      </div>
    </div>

    <p class="footer-text mb-3">
      © <?= date('Y') ?> <strong>Sipelakor</strong> — Sistem Pelaporan Keuangan Sekolah
    </p>
  </div>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
