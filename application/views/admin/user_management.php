<div class="content-wrapper mt-4 px-4">
  <h4 class="mb-4"><?php echo $title; ?></h4>

  <?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?php echo $this->session->flashdata('success'); ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php endif; ?>

  <!-- Form Tambah Pengguna -->
  <form action="<?php echo base_url('user_management/add'); ?>" method="post" class="mb-4">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
           value="<?php echo $this->security->get_csrf_hash(); ?>">

    <div class="row">
      <div class="col-md-3 mb-2"><input type="text" name="nama" class="form-control" placeholder="Nama Sekolah" required></div>
      <div class="col-md-3 mb-2"><input type="text" name="alamat" class="form-control" placeholder="Alamat"></div>
      <div class="col-md-3 mb-2"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
      <div class="col-md-3 mb-2"><input type="text" name="no_kontrol" class="form-control" placeholder="Kode Login (NPSN)" required></div>
    </div>

    <div class="row">
      <div class="col-md-3 mb-2"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
      <div class="col-md-2 mb-2">
        <select name="role" class="form-control" required>
          <option value="">Pilih Role</option>
          <option value="admin">Admin</option>
          <option value="sekolah">Sekolah</option>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <select name="cabang_id" class="form-control">
          <option value="">Pilih Cabang</option>
          <?php foreach ($cabang as $c): ?>
            <option value="<?php echo $c->id; ?>"><?php echo $c->nama; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <select name="jenjang_id" class="form-control">
          <option value="">Pilih Jenjang</option>
          <?php foreach ($jenjang as $j): ?>
            <option value="<?php echo $j->id; ?>"><?php echo $j->nama; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 mb-2"><input type="text" name="kepala_sekolah" class="form-control" placeholder="Kepala Sekolah"></div>
      <div class="col-md-3 mb-2"><input type="text" name="bendahara" class="form-control" placeholder="Bendahara Sekolah"></div>
      <div class="col-md-2 mb-2">
        <button type="submit" class="btn btn-primary w-100">Tambah</button>
      </div>
    </div>
  </form>

  <!-- Tabel -->
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="thead-light text-center">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Kode</th>
          <th>Role</th>
          <th>Jenjang</th>
          <th>Kepala Sekolah</th>
          <th>Bendahara</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($users as $u): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $u->nama; ?></td>
          <td><?php echo $u->email; ?></td>
          <td><?php echo $u->no_kontrol; ?></td>
          <td><?php echo $u->role; ?></td>
          <td><?php echo isset($u->jenjang_id) ? $u->jenjang_id : '-'; ?></td>
          <td><?php echo isset($u->kepala_sekolah) ? $u->kepala_sekolah : '-'; ?></td>
          <td><?php echo isset($u->bendahara) ? $u->bendahara : '-'; ?></td>
          <td class="text-center">
            <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?php echo $u->id; ?>">Edit</a>
            <a href="<?php echo base_url('admin/user_management/delete/'.$u->id); ?>" onclick="return confirm('Hapus pengguna ini?')" class="btn btn-danger btn-sm">Hapus</a>
          </td>
        </tr>

        <!-- Modal Edit -->
        <div class="modal fade" id="edit<?php echo $u->id; ?>" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <form action="<?php echo base_url('admin/user_management/update/'.$u->id); ?>" method="post" class="modal-content">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
                     value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div class="modal-header"><h5>Edit Data Sekolah</h5></div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6 mb-2"><input type="text" name="nama" value="<?php echo $u->nama; ?>" class="form-control" required></div>
                  <div class="col-md-6 mb-2"><input type="text" name="alamat" value="<?php echo isset($u->alamat)?$u->alamat:''; ?>" class="form-control"></div>
                  <div class="col-md-6 mb-2"><input type="email" name="email" value="<?php echo $u->email; ?>" class="form-control" required></div>
                  <div class="col-md-6 mb-2"><input type="text" name="no_kontrol" value="<?php echo $u->no_kontrol; ?>" class="form-control" required></div>
                  <div class="col-md-6 mb-2"><input type="password" name="password" placeholder="(kosongkan jika tidak diubah)" class="form-control"></div>
                  <div class="col-md-3 mb-2">
                    <select name="role" class="form-control">
                      <option value="admin" <?php echo ($u->role=='admin'?'selected':''); ?>>Admin</option>
                      <option value="sekolah" <?php echo ($u->role=='sekolah'?'selected':''); ?>>Sekolah</option>
                    </select>
                  </div>
                  <div class="col-md-3 mb-2">
                    <select name="jenjang_id" class="form-control">
                      <option value="">Pilih Jenjang</option>
                      <?php foreach ($jenjang as $j): ?>
                        <option value="<?php echo $j->id; ?>" <?php echo ($u->jenjang_id==$j->id?'selected':''); ?>>
                          <?php echo $j->nama; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6 mb-2"><input type="text" name="kepala_sekolah" value="<?php echo isset($u->kepala_sekolah)?$u->kepala_sekolah:''; ?>" class="form-control"></div>
                  <div class="col-md-6 mb-2"><input type="text" name="bendahara" value="<?php echo isset($u->bendahara)?$u->bendahara:''; ?>" class="form-control"></div>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
