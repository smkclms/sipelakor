
<style>
#togglePassword { transition: color 0.2s; }
#togglePassword:hover { color: #0d6efd; }
</style>

<div class="container mt-4">
  <h4 class="mb-3"><i class="fas fa-user-circle"></i> Profil Sekolah</h4>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <div class="row">
    <!-- PROFIL SEKOLAH -->
    <div class="col-md-7">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <strong>Data Profil Sekolah</strong>
          <button class="btn btn-light btn-sm" id="btnEditProfil"><i class="fas fa-pen"></i> Edit Profil</button>
        </div>
        <div class="card-body">
          <!-- Mode Lihat -->
          <div id="profilView">
            <p><strong>Nama Sekolah:</strong> <?= $user->nama ?></p>
            <p><strong>Kecamatan:</strong> <?= $user->alamat ?></p>
            <p><strong>Email:</strong> <?= $user->email ?></p>
            <p><strong>Kepala Sekolah:</strong> <?= $user->kepala_sekolah ?></p>
            <p><strong>Bendahara:</strong> <?= $user->bendahara ?></p>
          </div>

          <!-- Mode Edit -->
          <form id="profilEdit" method="post" action="<?= base_url('profil/update') ?>" style="display:none;">
            <input type="hidden" 
                   name="<?= $this->security->get_csrf_token_name(); ?>" 
                   value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-group mb-2">
              <label>Nama Sekolah</label>
              <input type="text" name="nama" value="<?= $user->nama ?>" class="form-control" required>
            </div>
            <div class="form-group mb-2">
              <label>Kecamatan</label>
              <input type="text" name="alamat" value="<?= $user->alamat ?>" class="form-control">
            </div>
            <div class="form-group mb-2">
              <label>Email</label>
              <input type="email" name="email" value="<?= $user->email ?>" class="form-control" required>
            </div>
            <div class="form-group mb-2">
              <label>Kepala Sekolah</label>
              <input type="text" name="kepala_sekolah" value="<?= $user->kepala_sekolah ?>" class="form-control">
            </div>
            <div class="form-group mb-2">
              <label>Bendahara</label>
              <input type="text" name="bendahara" value="<?= $user->bendahara ?>" class="form-control">
            </div>
            <div class="mt-3 text-end">
              <button type="button" id="btnCancelEdit" class="btn btn-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-success btn-sm">💾 Simpan Perubahan</button>
            </div>
            <?php if (!empty($user->updated_at)): ?>
  <div class="text-muted small mt-3">
    <i class="fas fa-clock"></i> Terakhir diubah: 
    <?= date('d M Y H:i', strtotime($user->updated_at)) ?>
  </div>
<?php endif; ?>
          </form>
        </div>
      </div>
    </div>

    <!-- GANTI PASSWORD -->
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
          <strong>Ganti Password</strong>
        </div>
        <div class="card-body">
          <form method="post" action="<?= base_url('profil/ganti_password') ?>">
            <input type="hidden" 
                   name="<?= $this->security->get_csrf_token_name(); ?>" 
                   value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-group mb-2 position-relative">
              <label>Password Baru</label>
              <input type="password" name="password_baru" id="passwordBaru" class="form-control" required>
              <i class="fas fa-eye position-absolute" id="togglePassword" 
                 style="right:10px; top:38px; cursor:pointer; color:gray;"></i>
            </div>

            <button type="submit" class="btn btn-primary mt-2">🔒 Ubah Password</button>
          </form>
          <?php if (!empty($user->password_changed_at)): ?>
  <div class="text-muted small mt-3">
    <i class="fas fa-key"></i> Password terakhir diubah: 
    <?= date('d M Y H:i', strtotime($user->password_changed_at)) ?>
  </div>
<?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  const btnEdit = document.getElementById('btnEditProfil');
  const btnCancel = document.getElementById('btnCancelEdit');
  const view = document.getElementById('profilView');
  const edit = document.getElementById('profilEdit');

  btnEdit.addEventListener('click', () => {
    view.style.display = 'none';
    edit.style.display = 'block';
  });

  btnCancel.addEventListener('click', () => {
    edit.style.display = 'none';
    view.style.display = 'block';
  });

  // Toggle Password
  const toggle = document.getElementById('togglePassword');
  const passInput = document.getElementById('passwordBaru');
  toggle.addEventListener('click', () => {
    const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passInput.setAttribute('type', type);
    toggle.classList.toggle('fa-eye-slash');
  });
});
</script>
