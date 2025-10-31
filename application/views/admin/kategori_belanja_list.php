<div class="container mt-4">
    <h4 class="mb-4 text-primary"><?= $title ?></h4>

    <!-- Flash message -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- Form Tambah -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Tambah Kategori Belanja</div>
        <div class="card-body">
            <form action="<?= base_url('kategori_belanja/tambah') ?>" method="post">
    <!-- CSRF Token -->
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
           value="<?= $this->security->get_csrf_hash(); ?>" />

    <div class="form-group mb-2">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" 
               placeholder="Masukkan nama kategori..." required>
    </div>
    <button type="submit" class="btn btn-success mt-2">Simpan</button>
</form>

        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-header bg-secondary text-white">Daftar Kategori Belanja</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Kategori</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($kategori as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($k->nama) ?></td>
                            <td>
                                <a href="<?= base_url('kategori_belanja/edit/'.$k->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= base_url('kategori_belanja/hapus/'.$k->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($kategori)): ?>
                        <tr><td colspan="3" class="text-center text-muted">Belum ada kategori.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
