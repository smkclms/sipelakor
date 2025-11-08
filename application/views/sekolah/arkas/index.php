<div class="content-wrapper mt-4 px-4">
  <h4 class="mb-3"><?= $judul ?></h4>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <!-- FORM UPLOAD -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" action="<?= base_url('arkas/upload') ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="form-group mb-3">
          <label>Upload File RKAS (Excel)</label>
          <input type="file" name="file_arkas" class="form-control" accept=".xls,.xlsx" required>
          <small class="text-muted">Format: .xls / .xlsx — Maks 4MB</small>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fa fa-upload"></i> Upload File
        </button>
      </form>
    </div>
  </div>

  <!-- DAFTAR FILE -->
  <div class="card mb-4">
    <div class="card-body">
      <h5>Daftar File RKAS</h5>
      <table class="table table-bordered mt-3">
        <thead class="table-light text-center">
          <tr>
            <th width="5%">No</th>
            <th>Nama File</th>
            <th width="20%">Tanggal Upload</th>
            <th width="30%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($uploads)): ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada file diunggah</td></tr>
          <?php else: $no=1; foreach ($uploads as $u): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($u->nama_file) ?></td>
              <td class="text-center"><?= date('d/m/Y H:i', strtotime($u->tanggal_upload)) ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-info btn-preview" data-id="<?= $u->id_upload ?>">
                  <i class="fa fa-eye"></i> Lihat
                </button>
                <a href="<?= base_url($u->file_path) ?>" target="_blank" class="btn btn-sm btn-success">
                  <i class="fa fa-download"></i> Unduh
                </a>
                <a href="<?= base_url('arkas/hapus/'.$u->id_upload) ?>"
                   onclick="return confirm('Yakin hapus file ini?')"
                   class="btn btn-sm btn-danger">
                   <i class="fa fa-trash"></i> Hapus
                </a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODAL PREVIEW -->
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

<!-- SCRIPT PREVIEW -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
  var modal = new bootstrap.Modal(document.getElementById('previewModal'));
  var $previewContent = $('#previewContent');

  $('.btn-preview').on('click', function(){
    var id = $(this).data('id');
    $previewContent.html(
      '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat file...</p>'
    );
    modal.show();

    $.ajax({
      url: '<?= base_url('arkas/preview_ajax/') ?>' + id,
      type: 'GET',
      success: function(html){
        $previewContent.html(html);
      },
      error: function(){
        $previewContent.html('<div class="alert alert-danger">Gagal memuat preview file.</div>');
      }
    });
  });
});
</script>
