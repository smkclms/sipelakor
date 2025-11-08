<div class="content-wrapper mt-4 px-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4><?= $judul ?></h4>
    <a href="<?= base_url('rkas/tambah') ?>" class="btn btn-primary">
      <i class="ti-plus"></i> Tambah Rencana
    </a>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light text-center">
          <tr>
            <th>No</th>
            <th>Nama Kegiatan</th>
            <th>Sumber Anggaran</th>
            <th>Total Rencana</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rkas)): ?>
          <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data RKAS</td>
          </tr>
          <?php else: $no=1; foreach($rkas as $r): ?>
          <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= $r->nama_kegiatan ?></td>
            <td><?= $r->sumber_anggaran ?></td>
            <td class="text-end"><?= number_format($r->total_rencana,0,',','.') ?></td>
            <td class="text-center">
              <a href="<?= base_url('rkas/hapus/'.$r->id_rkas) ?>"
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('Yakin hapus data ini?')">
                 Hapus
              </a>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
