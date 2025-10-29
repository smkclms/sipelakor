<div class="container mt-4">
  <h4>Buat Pembelanjaan Baru</h4>

  <form method="post">
    <div class="form-row mt-3">
      <div class="col-md-3">
        <label>Tanggal Transaksi</label>
        <input type="date" name="tanggal" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label>Kegiatan Belanja</label>
        <input type="text" name="kegiatan" class="form-control" placeholder="Contoh: Pembelian ATK Semester 1" required>
      </div>
      <div class="col-md-3">
        <label>Jenis Belanja</label>
        <select name="jenis_belanja_id" class="form-control" required>
          <option value="">-- Pilih Jenis Belanja --</option>
          <?php foreach($kategori as $k): ?>
            <option value="<?= $k->id ?>"><?= $k->nama ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row mt-3">
      <div class="col-md-3">
        <label>Platform Belanja</label>
        <select name="platform" class="form-control">
          <option value="Non_SIPLAH">Non SIPLAH</option>
          <option value="SIPLAH">SIPLAH</option>
        </select>
      </div>
      <div class="col-md-4">
        <label>Nama Toko/Penyedia</label>
        <input type="text" name="nama_toko" class="form-control" placeholder="Nama toko atau penyedia">
      </div>
      <div class="col-md-5">
        <label>Alamat Toko</label>
        <input type="text" name="alamat_toko" class="form-control" placeholder="Alamat lengkap toko">
      </div>
    </div>

    <div class="form-row mt-3">
      <div class="col-md-3">
        <label>Pembayaran</label>
        <select name="pembayaran" class="form-control">
          <option value="Tunai">Tunai</option>
          <option value="Non-Tunai">Non-Tunai</option>
        </select>
      </div>
      <div class="col-md-3">
        <label>No Rekening</label>
        <input type="text" name="no_rekening" class="form-control" placeholder="Nomor rekening toko">
      </div>
      <div class="col-md-3">
        <label>Nama Bank</label>
        <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: BRI, BNI, Mandiri">
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">Simpan Pembelanjaan</button>
      <a href="<?= base_url('rekap') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div>
