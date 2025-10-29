<div class="container mt-4">
  <h3 class="text-primary mb-4">Data Sekolah & Anggaran</h3>
  <form method="get" class="form-inline mb-3">
  <label class="mr-2">Filter Jenjang:</label>
  <select name="jenjang_id" class="form-control" onchange="this.form.submit()">
    <option value="">-- Semua Jenjang --</option>
    <?php foreach ($jenjang as $j): ?>
      <option value="<?= $j->id ?>" <?= ($selected_jenjang == $j->id) ? 'selected' : '' ?>>
        <?= $j->nama ?>
      </option>
    <?php endforeach; ?>
  </select>
</form>
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th style="width:40px;">No</th>
          <th>Nama Sekolah</th>
          <th>NPSN</th>
          <th>Sumber Anggaran</th>
          <th>Jumlah Anggaran</th>
          <th>Penggunaan</th>
          <th>Sisa</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $grand_total = 0;
        $grand_penggunaan = 0;
        $grand_sisa = 0;

        if (!empty($sekolah)):
          foreach ($sekolah as $s):
            // pastikan ada anggaran, kalau tidak treat sebagai array kosong
            $anggaran_list = isset($s->anggaran) ? $s->anggaran : array();
            $rowspan = (is_array($anggaran_list) ? count($anggaran_list) : 0);
            if ($rowspan <= 0) {
              // jika tidak ada anggaran, tampilkan satu baris kosong untuk sekolah ini
              ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= isset($s->nama) ? $s->nama : '-' ?></td>
                <td><?= isset($s->no_kontrol) ? $s->no_kontrol : '-' ?></td>
                <td colspan="4" class="text-muted text-center">Belum ada data anggaran untuk sekolah ini.</td>
              </tr>
              <?php
              $no++;
              continue;
            }

            $first_row = true;
            foreach ($anggaran_list as $a):
              // ambil total penggunaan dari tb_rekap_pembelanjaan berdasarkan sumber (fallback aman)
              $this->db->select_sum('nilai_transaksi', 'total');
              $this->db->where('sekolah_id', $s->id);
              // jika kolom sumber_anggaran_id ada pada rekap, gunakan where; jika tidak, fallback tidak dipakai
              // (kita asumsikan kolom ada karena kamu menambahkannya)
              if (isset($a->sumber_id)) {
                $this->db->where('sumber_anggaran_id', $a->sumber_id);
              } elseif (isset($a->nama_sumber)) {
                $this->db->like('kegiatan', $a->nama_sumber);
              }
              $penggunaan_row = $this->db->get('tb_rekap_pembelanjaan')->row();

              $penggunaan = isset($penggunaan_row->total) ? $penggunaan_row->total : 0;
              $jumlah = isset($a->jumlah) ? $a->jumlah : 0;
              $sisa = $jumlah - $penggunaan;

              $grand_total += $jumlah;
              $grand_penggunaan += $penggunaan;
              $grand_sisa += $sisa;
        ?>
        <tr>
          <?php if ($first_row): ?>
            <td rowspan="<?= $rowspan ?>"><?= $no ?></td>
            <td rowspan="<?= $rowspan ?>"><?= isset($s->nama) ? $s->nama : '-' ?></td>
            <td rowspan="<?= $rowspan ?>"><?= isset($s->no_kontrol) ? $s->no_kontrol : '-' ?></td>
            <?php $first_row = false; ?>
          <?php endif; ?>

          <td><?= isset($a->nama_sumber) ? $a->nama_sumber : (isset($a->sumber_id) ? 'Sumber #' . $a->sumber_id : '-') ?></td>
          <td>Rp <?= number_format($jumlah, 0, ',', '.') ?></td>
          <td>Rp <?= number_format($penggunaan, 0, ',', '.') ?></td>
          <td><strong>Rp <?= number_format($sisa, 0, ',', '.') ?></strong></td>
        </tr>
        <?php
            endforeach; // per sumber
            $no++;
          endforeach; // per sekolah
        else:
        ?>
        <tr>
          <td colspan="7" class="text-center text-muted">Belum ada data sekolah.</td>
        </tr>
        <?php endif; ?>
      </tbody>
      <tfoot class="table-secondary">
        <tr>
          <th colspan="4" class="text-end">TOTAL KESELURUHAN:</th>
          <th>Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
          <th>Rp <?= number_format($grand_penggunaan, 0, ',', '.') ?></th>
          <th><strong>Rp <?= number_format($grand_sisa, 0, ',', '.') ?></strong></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<style>
  .table th, .table td { vertical-align: middle; }
  .table-dark th { text-align: center; }
</style>
