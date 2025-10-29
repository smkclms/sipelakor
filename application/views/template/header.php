<!doctype html>
<html lang="id">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sipelakor - Sistem Pelaporan Keuangan</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <script>
  const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
  const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
</script>
</head>

<style>
  .navbar .mx-auto {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  color: #333;
}

</style>
<body>
  <?php
$tahun_aktif = '-';
if ($this->session->userdata('tahun_id')) {
  $tahun_row = $this->db->get_where('tb_tahun_anggaran', [
    'id' => $this->session->userdata('tahun_id')
  ])->row();
  if ($tahun_row) {
    $tahun_aktif = $tahun_row->tahun;
  }
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Sipelakor</a>

  <!-- 🔹 Tambahan teks di tengah -->
  <div class="mx-auto text-center font-weight-bold">
    Tahun Anggaran: <?= $tahun_aktif ?>
  </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navTop">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navTop">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link">Halo, <?= $this->session->userdata('nama') ?></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout') ?>">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
