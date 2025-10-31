<form action="<?= base_url('kategori_belanja/edit/'.$kategori->id) ?>" method="post">
    <!-- CSRF Token -->
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
           value="<?= $this->security->get_csrf_hash(); ?>" />

    <div class="form-group mb-3">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" 
               value="<?= htmlspecialchars($kategori->nama) ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    <a href="<?= base_url('kategori_belanja') ?>" class="btn btn-secondary">Kembali</a>
</form>
