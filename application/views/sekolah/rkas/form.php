<div class="content-wrapper mt-4 px-4">
  <h4 class="mb-3"><?= $judul ?></h4>

  <form method="post" action="<?= base_url('rkas/simpan') ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="card">
      <div class="card-body">
        <div class="form-group mb-3">
          <label>Nama Kegiatan</label>
          <input type="text" name="nama_kegiatan" class="form-control" required>
        </div>

        <div class="form-group mb-3">
          <label>Sumber Anggaran</label>
          <select name="sumber_anggaran" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="BOS">BOS</option>
            <option value="BOP">BOP</option>
            <option value="Komite">Komite</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>

        <div class="form-group mb-3">
          <label>Uraian</label>
          <textarea name="uraian" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group mb-3">
          <label>Total Rencana</label>
          <input type="text" name="total_rencana" class="form-control" required>
        </div>
      </div>

      <div class="card-footer text-end">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="<?= base_url('rkas') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </div>
  </form>
</div>
