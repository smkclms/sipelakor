<div class="container mt-4">
  <h4 class="text-primary mb-3">Kelola Kodering</h4>
  <form method="post" action="<?= base_url('kodering/import_excel') ?>" enctype="multipart/form-data" class="mb-4">
  <div class="row">
    <div class="col-md-4">
      <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx" required>
    </div>
    <a href="<?= base_url('kodering/download_template') ?>" class="btn btn-primary mb-3">⬇️ Download Template Kodering</a>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success">📂 Import Excel</button>
    </div>
  </div>
</form>


  <form method="post" action="<?= base_url('kodering/tambah') ?>" class="mb-4">
    <div class="row">
      <div class="col-md-3">
        <input type="text" name="kode" class="form-control" placeholder="Kode Kodering (misal 5.1.02.01.01.0055)" required>
      </div>
      <div class="col-md-5">
        <input type="text" name="nama" class="form-control" placeholder="Nama Kodering" required>
      </div>
      <div class="col-md-3">
        <select name="kategori_id" class="form-control">
          <?php foreach ($kategori_belanja as $k): ?>
            <option value="<?= $k->id ?>"><?= $k->nama ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-primary">+ Tambah</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>Kode</th>
          <th>Nama</th>
          <th>Kategori</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($kodering as $k): ?>
        <tr>
          <td><?= $k->kode ?></td>
          <td><?= $k->nama ?></td>
          <td><?= $k->kategori ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
