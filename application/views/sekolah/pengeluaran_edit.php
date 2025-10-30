<div class="container mt-4">
  <h4 class="mb-4 text-primary">Edit Pengeluaran</h4>

  <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php elseif($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <?= form_open_multipart('pengeluaran/update'); ?>
  <input type="hidden" name="id" value="<?= $pengeluaran->id ?>">

  <div class="form-row">
    <div class="col-md-3 mb-2">
      <label>Sumber Anggaran</label>
      <select name="sumber_anggaran_id" class="form-control" required>
        <?php foreach($sumber_anggaran as $sa): ?>
          <option value="<?= trim($sa->id) ?>" <?= (int)$sa->id === (int)$pengeluaran->sumber_anggaran_id ? 'selected' : '' ?>>
            <?= htmlspecialchars(trim($sa->nama), ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4 mb-2">
      <label>Kegiatan</label>
      <input type="text" name="kegiatan" class="form-control" value="<?= $pengeluaran->kegiatan ?>" required>
    </div>

    <div class="col-md-2 mb-2">
      <label>Tanggal</label>
      <input type="date" name="tanggal" class="form-control" value="<?= $pengeluaran->tanggal ?>" required>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-4 mb-2">
      <label>Kodering</label>
      <select name="kodering_id" class="form-control" required>
        <?php foreach($kodering as $k): ?>
          <option value="<?= $k->id ?>" <?= $k->id == $pengeluaran->kodering_id ? 'selected' : '' ?>>
            <?= $k->kode ?> - <?= $k->nama ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3 mb-2">
      <label>Jenis Belanja</label>
      <select name="jenis_belanja_id" class="form-control" required>
        <?php foreach($kategori_belanja as $k): ?>
          <option value="<?= $k->id ?>" <?= $k->id == $pengeluaran->jenis_belanja_id ? 'selected' : '' ?>><?= $k->nama ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3 mb-2">
      <label>Jumlah (Rp)</label>
      <input type="number" name="jumlah" class="form-control" value="<?= $pengeluaran->jumlah ?>" required>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-4 mb-2">
      <label>Uraian</label>
      <input type="text" name="uraian" class="form-control" value="<?= $pengeluaran->uraian ?>" required>
    </div>

    <div class="col-md-2 mb-2">
      <label>Platform</label>
      <select name="platform" class="form-control">
        <option value="Non_SIPLAH" <?= $pengeluaran->platform == 'Non_SIPLAH' ? 'selected' : '' ?>>Non SIPLAH</option>
        <option value="SIPLAH" <?= $pengeluaran->platform == 'SIPLAH' ? 'selected' : '' ?>>SIPLAH</option>
      </select>
    </div>

    <!-- ✅ Tambahan Baru -->
    <div class="col-md-3 mb-2">
      <label>Marketplace / Mitra SIPLAH</label>
      <input type="text" name="marketplace" class="form-control" placeholder="Nama Marketplace" value="<?= $pengeluaran->marketplace ?>">
    </div>
    <!-- ✅ Selesai Tambahan -->

    <div class="col-md-3 mb-2">
      <label>Nama Toko</label>
      <input type="text" name="nama_toko" class="form-control" value="<?= $pengeluaran->nama_toko ?>">
    </div>

    <div class="col-md-3 mb-2">
      <label>Alamat Toko</label>
      <input type="text" name="alamat_toko" class="form-control" value="<?= $pengeluaran->alamat_toko ?>">
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-2 mb-2">
      <label>Pembayaran</label>
      <select name="pembayaran" class="form-control">
        <option value="Tunai" <?= $pengeluaran->pembayaran == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
        <option value="Non-Tunai" <?= $pengeluaran->pembayaran == 'Non-Tunai' ? 'selected' : '' ?>>Non-Tunai</option>
      </select>
    </div>

    <div class="col-md-2 mb-2">
      <label>No Rekening</label>
      <input type="text" name="no_rekening" class="form-control" value="<?= $pengeluaran->no_rekening ?>">
    </div>

    <div class="col-md-2 mb-2">
      <label>Nama Bank</label>
      <input type="text" name="nama_bank" class="form-control" value="<?= $pengeluaran->nama_bank ?>">
    </div>

    <div class="col-md-3 mb-2">
      <label>Bukti (opsional)</label>
      <input type="file" name="bukti" class="form-control">
      <?php if($pengeluaran->bukti): ?>
        <small>File saat ini: <a href="<?= base_url('uploads/bukti/'.$pengeluaran->bukti) ?>" target="_blank">Lihat</a></small>
      <?php endif; ?>
    </div>
  </div>

  <div class="form-row mt-3">
    <div class="col-md-12 text-right">
      <a href="<?= base_url('pengeluaran') ?>" class="btn btn-secondary">⬅️ Kembali</a>
      <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
    </div>
  </div>
  <?= form_close(); ?>
</div>
