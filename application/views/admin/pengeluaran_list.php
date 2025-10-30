<div class="container-fluid mt-4">
  <h4 class="mb-4 text-primary text-center font-weight-bold">
    Verifikasi Pengeluaran Sekolah Tahun <?= $tahun ?>
  </h4>

  <!-- 🔍 FILTER -->
  <form method="get" class="form-inline mb-4 justify-content-center">
    <label class="mr-2 font-weight-bold text-dark">Sekolah:</label>
    <select name="sekolah_id" class="form-control mr-3">
      <option value="">Semua Sekolah</option>
      <?php foreach($sekolah as $s): ?>
        <option value="<?= $s->id ?>" <?= $sekolah_id == $s->id ? 'selected' : '' ?>>
          <?= $s->nama ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label class="mr-2 font-weight-bold text-dark">Status:</label>
    <select name="status" class="form-control mr-3">
      <option value="">Semua Status</option>
      <option value="Menunggu" <?= $status == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
      <option value="Disetujui" <?= $status == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
      <option value="Ditolak" <?= $status == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fas fa-search"></i> Filter
    </button>
  </form>

  <!-- ✅ FLASH MESSAGE -->
  <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show text-center shadow-sm" role="alert">
      <?= $this->session->flashdata('success') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php elseif($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show text-center shadow-sm" role="alert">
      <?= $this->session->flashdata('warning') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <!-- 📋 TABEL DATA -->
  <?php if (empty($pengeluaran)): ?>
    <div class="alert alert-warning text-center font-weight-bold">
      Tidak ada data pengeluaran sesuai filter.
    </div>
  <?php else: ?>
  <form method="post" action="<?= base_url('pengeluaran_admin/verifikasi_massal') ?>">
    <!-- 🛡️ CSRF PROTECTION -->
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
           value="<?= $this->security->get_csrf_hash(); ?>" />

    <div class="table-responsive shadow-sm rounded">
      <table class="table table-bordered table-striped table-hover mb-0 w-100">
        <thead class="thead-dark text-center">
          <tr>
            <th style="width:40px;"><input type="checkbox" id="checkAll"></th>
            <th style="width:50px;">No</th>
            <th>Sekolah</th>
            <th>Sumber Anggaran</th>
            <th>Jenis Belanja</th>
            <th>Kodering</th>
            <th>Uraian</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($pengeluaran as $p): ?>
            <tr>
              <td class="text-center align-middle">
                <input type="checkbox" name="ids[]" value="<?= $p->id ?>">
              </td>
              <td class="text-center align-middle"><?= $no++ ?></td>
              <td class="align-middle font-weight-bold text-dark"><?= $p->nama_sekolah ?></td>
              <td class="align-middle"><?= $p->sumber_anggaran ?></td>
              <td class="align-middle"><?= $p->nama_jenis_belanja ?></td>
              <td class="align-middle"><?= $p->kode ?> - <?= $p->nama_kodering ?></td>
              <td class="align-middle"><?= $p->uraian ?></td>
              <td class="align-middle text-right font-weight-bold text-success">
                Rp <?= number_format($p->jumlah, 0, ',', '.') ?>
              </td>
              <td class="text-center align-middle"><?= date('d-m-Y', strtotime($p->tanggal)) ?></td>
              <td class="text-center align-middle">
                <?php if($p->status == 'Menunggu'): ?>
                  <span class="badge badge-warning">Menunggu</span>
                <?php elseif($p->status == 'Disetujui'): ?>
                  <span class="badge badge-success">Disetujui</span>
                <?php else: ?>
                  <span class="badge badge-danger">Ditolak</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- ✅ AKSI MASSAL -->
    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="mb-3 mb-md-0">
        <button type="submit" name="aksi" value="Disetujui" class="btn btn-success btn-sm mr-2">
          <i class="fas fa-check"></i> Setujui Terpilih
        </button>
        <button type="submit" name="aksi" value="Ditolak" class="btn btn-danger btn-sm">
          <i class="fas fa-times"></i> Tolak Terpilih
        </button>
      </div>

      <!-- 🎯 PAGINATION di tengah -->
      <div class="mt-4">
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center custom-pagination mb-0">
            <?= $pagination ?>
          </ul>
        </nav>
      </div>
    </div>
  </form>
  <?php endif; ?>
</div>

<!-- ✅ SCRIPT: Ceklis Semua -->
<script>
  document.getElementById('checkAll').addEventListener('click', function(){
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>

<style>
  body { background-color: #f8f9fc; }
  .container-fluid { max-width: 98%; }
  table { background: #fff; }
  th, td { vertical-align: middle !important; }
  .table-responsive { margin-bottom: 1rem; }

  /* 🌈 Pagination Styling */
  .custom-pagination {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding-left: 0;
    margin: 0;
  }
  .custom-pagination a,
  .custom-pagination strong,
  .custom-pagination span {
    color: #007bff;
    border: 1px solid #dee2e6;
    background-color: #fff;
    padding: 6px 12px;
    margin: 0 3px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    font-weight: 500;
  }
  .custom-pagination a:hover {
    background-color: #007bff;
    color: #fff !important;
    border-color: #007bff;
  }
  .custom-pagination strong {
    background-color: #007bff;
    color: #fff !important;
    border-color: #007bff;
  }
  .custom-pagination span { color: #6c757d; }
</style>
