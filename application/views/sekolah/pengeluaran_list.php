<div class="container mt-4">
  <h4 class="mb-4 text-primary">Laporan Pengeluaran Sekolah Tahun <?= $tahun ?></h4>

  <!-- 🔹 Tombol Import Excel & Download Template -->
  <div class="mb-3">
    <form action="<?= base_url('pengeluaran/import_excel') ?>" method="post" enctype="multipart/form-data" class="form-inline">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
             value="<?= $this->security->get_csrf_hash(); ?>">

      <div class="form-group mr-2">
        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required>
      </div>

      <button type="submit" class="btn btn-primary mr-2">📂 Import Excel</button>
      <a href="<?= base_url('pengeluaran/download_template') ?>" class="btn btn-success">⬇️ Download Template</a>
    </form>
  </div>

  <!-- 🔹 Notifikasi -->
  <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php elseif($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <!-- 🔹 Form Tambah Pengeluaran Manual -->
  <form method="post" action="<?= base_url('pengeluaran/tambah') ?>" enctype="multipart/form-data" class="mb-4">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="form-row">
      <div class="col-md-3 mb-2">
        <select name="sumber_anggaran_id" class="form-control" required>
          <option value="">-- Pilih Sumber Anggaran --</option>
          <?php foreach($sumber_anggaran as $sa): ?>
            <option value="<?= $sa->id ?>"><?= $sa->nama ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4 mb-2">
        <input type="text" name="kegiatan" class="form-control" placeholder="Nama Kegiatan" required>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md-3 mb-2">
        <input type="text" name="invoice_no" class="form-control" placeholder="No Invoice / Virtual Account (opsional)">
      </div>

      <div class="col-md-2 mb-2">
        <input type="date" name="tanggal" class="form-control" required>
      </div>

      <div class="col-md-3 mb-2">
        <select name="kodering_id" class="form-control" required>
          <option value="">-- Pilih Kodering --</option>
          <?php foreach($kodering as $k): ?>
            <option value="<?= $k->id ?>"><?= $k->kode ?> - <?= $k->nama ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-3 mb-2">
        <select name="jenis_belanja_id" class="form-control" required>
          <option value="">-- Pilih Jenis Belanja --</option>
          <?php foreach($kategori_belanja as $k): ?>
            <option value="<?= $k->id ?>"><?= $k->nama ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4 mb-2">
        <input type="text" name="uraian" class="form-control" placeholder="Uraian Pengeluaran" required>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md-2 mb-2">
        <input type="number" name="jumlah" class="form-control" placeholder="Jumlah (Rp)" required>
      </div>

      <div class="col-md-2 mb-2">
        <select name="platform" class="form-control">
          <option value="Non_SIPLAH">Non SIPLAH</option>
          <option value="SIPLAH">SIPLAH</option>
        </select>
      </div>

      <div class="col-md-3 mb-2">
        <input type="text" name="nama_toko" class="form-control" placeholder="Nama Toko/Penyedia">
      </div>

      <div class="col-md-3 mb-2">
        <input type="text" name="alamat_toko" class="form-control" placeholder="Alamat Toko">
      </div>

      <div class="col-md-2 mb-2">
        <select name="pembayaran" class="form-control">
          <option value="Tunai">Tunai</option>
          <option value="Non-Tunai">Non-Tunai</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md-2 mb-2">
        <input type="text" name="no_rekening" class="form-control" placeholder="No Rekening">
      </div>

      <div class="col-md-2 mb-2">
        <input type="text" name="nama_bank" class="form-control" placeholder="Nama Bank">
      </div>

      <div class="col-md-4 mb-2">
        <input type="file" name="bukti" class="form-control">
      </div>

      <div class="col-md-4 mb-2 text-right">
        <button type="submit" class="btn btn-success">💾 Simpan</button>
      </div>
    </div>
  </form>

  <!-- 🔹 Tabel Data Pengeluaran -->
  <div class="table-responsive">
    <table class="table table-bordered table-sm mt-3">
      <thead class="thead-light">
        <tr>
          <th>No Invoice</th>
          <th>Sumber Anggaran</th>
          <th>Kegiatan</th>
          <th>Tanggal</th>
          <th>Kode</th>
          <th>Nama Kodering</th>
          <th>Uraian</th>
          <th>Jumlah</th>
          <th>Platform</th>
          <th>Nama Toko</th>
          <th>Pembayaran</th>
          <th>Bukti</th>
          <th>Status</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($pengeluaran)): ?>
          <?php foreach($pengeluaran as $p): ?>
            <tr>
              <td><?= $p->invoice_no ?></td>
              <td><?= $p->nama_sumber_anggaran ?></td>
              <td><?= $p->kegiatan ?></td>
              <td><?= $p->tanggal ?></td>
              <td><?= $p->kode ?></td>
              <td><?= $p->nama_kodering ?></td>
              <td><?= $p->uraian ?></td>
              <td>Rp <?= number_format($p->jumlah,0,',','.') ?></td>
              <td><?= $p->platform ?></td>
              <td><?= $p->nama_toko ?></td>
              <td><?= $p->pembayaran ?></td>
              <td>
                <?php if($p->bukti): ?>
                  <a href="<?= base_url('uploads/bukti/'.$p->bukti) ?>" target="_blank" class="btn btn-link btn-sm text-primary">📄 Lihat</a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td><?= $p->status ?></td>
              <td class="text-center">
                <a href="<?= base_url('pengeluaran/edit/'.$p->id) ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                <a href="<?= base_url('pengeluaran/hapus/'.$p->id) ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin ingin menghapus data ini?\nData rekap akan diperbarui otomatis.');">
                   🗑 Hapus
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="14" class="text-center text-muted">Belum ada data pengeluaran</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<!-- Tambahkan ini di bawah tabel, sebelum </div> terakhir -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  // Ambil nama token dari konfigurasi
  const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
  let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

  // Fungsi ambil token dari cookie CI
  function getCsrfFromCookie() {
    const name = 'csrf_cookie_name=';
    const decoded = decodeURIComponent(document.cookie);
    const parts = decoded.split(';');
    for (let i = 0; i < parts.length; i++) {
      let c = parts[i].trim();
      if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
    }
    return csrfHash;
  }

  // ✅ Setup global AJAX agar selalu bawa token & auto-refresh token baru
  $.ajaxSetup({
    beforeSend: function(xhr, settings) {
      if (settings.type === 'POST') {
        let currentToken = getCsrfFromCookie();
        if (typeof settings.data === 'string') {
          if (settings.data.indexOf(csrfName) === -1) {
            settings.data += (settings.data ? '&' : '') + csrfName + '=' + currentToken;
          }
        } else if (typeof settings.data === 'object') {
          settings.data = settings.data || {};
          settings.data[csrfName] = currentToken;
        }
      }
    },
    complete: function(xhr) {
      // Update token bila server kirimkan token baru via header
      let newToken = xhr.getResponseHeader('X-CSRF-Token');
      if (newToken) csrfHash = newToken;
    }
  });

  // ✅ Tambahkan token ke semua form (kalau belum ada)
  $('form').each(function(){
    if ($(this).find('input[name="'+csrfName+'"]').length === 0) {
      $('<input>').attr({ type:'hidden', name:csrfName, value:csrfHash }).appendTo(this);
    }
  });
});
</script>


