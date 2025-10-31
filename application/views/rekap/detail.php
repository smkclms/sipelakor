<div class="container mt-4">
  <h4>Detail Invoice: <?= $rekap->invoice_no ?></h4>
  <p><strong>Sekolah:</strong> <?= isset($rekap->nama_sekolah) ? $rekap->nama_sekolah : 'Sekolah Ini' ?></p>
  <p><strong>Kegiatan:</strong> <?= $rekap->kegiatan ?></p>
  <p><strong>Tanggal:</strong> <?= $rekap->tanggal ?></p>
  <p><strong>Jenis Belanja:</strong> <?= $rekap->nama_jenis_belanja ? $rekap->nama_jenis_belanja : '-' ?></p>
  <p><strong>Platform:</strong> <?= $rekap->platform ?></p>
  <p><strong>Pembayaran:</strong> <?= $rekap->pembayaran ?></p>
  <p><strong>Total Nilai Transaksi:</strong> Rp <?= number_format($rekap->nilai_transaksi,0,',','.') ?></p>

  <hr>
  <h5>Daftar Item Pengeluaran</h5>

  <div class="table-responsive">
    <table class="table table-bordered table-sm">
      <thead class="thead-light">
        <tr>
          <th>Tanggal</th>
          <th>Kode</th>
          <th>Nama Kodering</th>
          <th>Uraian</th>
          <th>Jumlah</th>
          <th>Bukti</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $total_jumlah = 0; // 🔹 variabel untuk total
        foreach($pengeluaran as $p): 
          $total_jumlah += $p->jumlah; // 🔹 jumlahkan setiap baris
        ?>
          <tr>
            <td><?= $p->tanggal ?></td>
            <td><?= $p->kode ?></td>
            <td><?= $p->nama_kodering ?></td>
            <td><?= $p->uraian ?></td>
            <td>Rp <?= number_format($p->jumlah,0,',','.') ?></td>
            <td>
              <?php if($p->bukti): ?>
                <a href="<?= base_url('uploads/bukti/'.$p->bukti) ?>" target="_blank">Lihat</a>
              <?php endif; ?>
            </td>
            <td><?= $p->status ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>

      <!-- 🔹 Tambahkan baris total di bawah -->
      <tfoot>
        <tr class="font-weight-bold bg-light">
          <td colspan="4" class="text-right">Jumlah Total:</td>
          <td>Rp <?= number_format($total_jumlah, 0, ',', '.') ?></td>
          <td colspan="2"></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <a href="<?= base_url(($this->session->userdata('role')=='admin') ? 'rekap/admin' : 'rekap') ?>" class="btn btn-secondary">Kembali</a>
</div>

<style>
/* === RESPONSIVE TABEL === */
.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  margin-bottom: 1rem;
}

.table {
  min-width: 800px;
}

.table th,
.table td {
  white-space: nowrap;
}

.table-responsive::-webkit-scrollbar {
  height: 6px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 8px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background-color: #aaa;
}

tfoot td {
  font-weight: 600;
}
</style>
