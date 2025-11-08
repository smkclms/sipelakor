<div class="content-wrapper mt-4 px-4">
  <h4 class="mb-3"><?= $judul ?></h4>

  <div class="card">
    <div class="card-body">
      <h5>Daftar RKAS Sekolah</h5>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light text-center">
            <tr>
              <th style="width:5%">No</th>
              <th style="width:25%">Nama Sekolah</th>
              <th>Daftar RKAS</th>
              <th style="width:20%">Tanggal Upload</th>
              <th style="width:20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (empty($uploads)):
              echo '<tr><td colspan="5" class="text-center text-muted">Belum ada data RKAS yang diunggah</td></tr>';
            else:
              // Group data by sekolah
              $grouped = [];
              foreach ($uploads as $r) {
                $grouped[$r->sekolah_id]['nama'] = $r->nama_sekolah;
                $grouped[$r->sekolah_id]['data'][] = $r;
              }

              $no = 1;
              foreach ($grouped as $sekolah_id => $item):
                $rowspan = count($item['data']);
                $first = true;
                foreach ($item['data'] as $r):
            ?>
            <tr>
              <?php if ($first): ?>
                <td class="text-center align-middle" rowspan="<?= $rowspan ?>"><?= $no++ ?></td>
                <td class="align-middle" rowspan="<?= $rowspan ?>"><?= htmlspecialchars($item['nama']) ?></td>
              <?php endif; ?>
              <td><?= htmlspecialchars($r->nama_file) ?></td>
              <td class="text-center"><?= date('d/m/Y H:i', strtotime($r->tanggal_upload)) ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-info btn-preview" data-id="<?= $r->id_upload ?>">
                  <i class="fa fa-eye"></i> Lihat
                </button>
                <a href="<?= base_url($r->file_path) ?>" target="_blank" class="btn btn-sm btn-success">
                  <i class="fa fa-download"></i> Unduh
                </a>
                <a href="<?= base_url('arkas/hapus/'.$r->id_upload) ?>"
                   onclick="return confirm('Yakin hapus file ini?')"
                   class="btn btn-sm btn-danger">
                   <i class="fa fa-trash"></i> Hapus
                </a>
              </td>
            </tr>
            <?php $first = false; endforeach; endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Preview -->
  <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white py-2">
          <h5 class="modal-title">Preview File RKAS</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="previewContent" class="text-center text-muted">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat file...</p>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  var modal = new bootstrap.Modal(document.getElementById('previewModal'));
  var $preview = $('#previewContent');

  $('.btn-preview').on('click', function(){
    var id = $(this).data('id');
    $preview.html('<div class="spinner-border text-primary"></div><p>Memuat file...</p>');
    modal.show();
    $.get('<?= base_url('arkas_admin/preview_ajax/') ?>' + id, function(html){
      $preview.html(html);
    }).fail(function(){
      $preview.html('<div class="alert alert-danger">Gagal memuat preview file.</div>');
    });
  });
});
</script>
