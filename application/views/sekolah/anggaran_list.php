<div class="container mt-4">
    <h3 class="text-primary mb-4"><?= $title ?></h3>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
        <i class="fa fa-plus"></i> Tambah Anggaran
    </button>

    <?php
    $tahun_id = $this->session->userdata('tahun_id');
    $tahun = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    ?>
    <h5 class="text-secondary mb-3">
      🗓 Tahun Anggaran Aktif: <strong><?= $tahun ? $tahun->tahun : '-' ?></strong>
    </h5>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Sumber</th>
                    <th>Jumlah</th>
                    <th>Tersisa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($anggaran as $a): ?>
<tr>
  <td><?= $no++ ?></td>
  <td>
    <?php
      $nama_sumber = '-';
      foreach ($sumber_anggaran as $s) {
        if ($s->id == $a->sumber_id) {
          $nama_sumber = $s->nama;
          break;
        }
      }
      echo $nama_sumber;
    ?>
  </td>
  <td>Rp <?= number_format($a->jumlah, 0, ',', '.') ?></td>
  <td>Rp <?= number_format($a->tersisa, 0, ',', '.') ?></td>
  <td>
    <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $a->id ?>">Edit</a>
    <a href="<?= base_url('anggaran/hapus/'.$a->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
  </td>
</tr>
<?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('anggaran/tambah') ?>">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Anggaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
                           value="<?= $this->security->get_csrf_hash(); ?>">

                    <div class="form-group">
                        <label>Sumber Anggaran</label>
                        <select name="sumber_id" class="form-control" required>
                            <option value="">-- Pilih Sumber --</option>
                            <?php if (!empty($sumber_anggaran)): ?>
                                <?php foreach($sumber_anggaran as $s): ?>
                                    <option value="<?= $s->id ?>"><?= $s->nama ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">(Belum ada sumber anggaran)</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
