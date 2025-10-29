<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Sipelakor</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color:#f5f5f5;">
  <div class="container mt-5" style="max-width:420px;">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="text-center mb-4">Login Sipelakor</h4>
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

  <div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
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


  <button type="submit" class="btn btn-primary btn-block">Masuk</button>
</form>

      </div>
    </div>
    <p class="text-center mt-3 text-muted">© <?= date('Y') ?> Sipelakor</p>
  </div>
</body>
</html>
