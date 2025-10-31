<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model([
      'Pengeluaran_model',
      'Kodering_model',
      'Kategori_belanja_model',
      'Sumber_anggaran_model',
      'Rekap_model',
      'Tahun_model'
    ]);
    $this->load->library(['session','upload','PHPExcel_lib']);

    if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'sekolah') {
      redirect('auth/login');
    }
  }

  // 🔹 Halaman utama daftar & input pengeluaran
  public function index() {
    $tahun_id = $this->session->userdata('tahun_id');
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

    $user_id = $this->session->userdata('user_id');

    $data['tahun'] = $tahun;
    $data['pengeluaran'] = $this->Pengeluaran_model->get_by_sekolah($user_id, $tahun_id);
    $data['kodering'] = $this->Kodering_model->get_all();
    $data['kategori_belanja'] = $this->Kategori_belanja_model->get_all();
    $data['sumber_anggaran'] = $this->Sumber_anggaran_model->get_all();

    $this->load->view('template/header');
    $this->load->view('template/sidebar_sekolah');
    $this->load->view('sekolah/pengeluaran_list', $data);
    $this->load->view('template/footer');
}


  // 🔹 Tambah pengeluaran manual
  public function tambah(){
    $tahun_aktif = $this->Tahun_model->get_aktif();
    $tahun = $tahun_aktif ? $tahun_aktif->tahun : date('Y');

    $config['upload_path'] = './uploads/bukti/';
    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
    $config['max_size'] = 2048;
    $this->upload->initialize($config);

    $bukti = null;
    if (!empty($_FILES['bukti']['name'])) {
      if ($this->upload->do_upload('bukti')) {
        $bukti = $this->upload->data('file_name');
      }
    }

    $invoice_no = $this->input->post('invoice_no') ?: 'INV-' . $tahun . '-' . str_pad(rand(1,9999), 4, '0', STR_PAD_LEFT);

    $data = [
      'invoice_no' => $invoice_no,
      'sekolah_id' => $this->session->userdata('user_id'),
      'user_id' => $this->session->userdata('user_id'),
      'sumber_anggaran_id' => $this->input->post('sumber_anggaran_id'),
      'kegiatan' => $this->input->post('kegiatan'),
      'kodering_id' => $this->input->post('kodering_id'),
      'jenis_belanja_id' => $this->input->post('jenis_belanja_id'),
      'tahun_anggaran' => $tahun,
      'tanggal' => $this->input->post('tanggal'),
      'uraian' => $this->input->post('uraian'),
      'jumlah' => $this->input->post('jumlah'),
      'platform' => $this->input->post('platform'),
      'marketplace' => $this->input->post('marketplace'),
      'nama_toko' => $this->input->post('nama_toko'),
      'alamat_toko' => $this->input->post('alamat_toko'),
      'pembayaran' => $this->input->post('pembayaran'),
      'no_rekening' => $this->input->post('no_rekening'),
      'nama_bank' => $this->input->post('nama_bank'),
      'bukti' => $bukti,
      'status' => 'Menunggu Verifikasi',
      'tahun' => $tahun
    ];

    $this->Pengeluaran_model->insert($data);

    // 🔹 Rekap otomatis berdasarkan invoice
    $total = $this->Pengeluaran_model->sum_by_invoice($invoice_no);
    $existing = $this->Rekap_model->get($invoice_no);

    if ($existing) {
        $this->Rekap_model->update_total($invoice_no, $total);
    } else {
        $this->Rekap_model->insert([
            'invoice_no' => $invoice_no,
            'sekolah_id' => $this->session->userdata('user_id'),
            'tanggal' => $this->input->post('tanggal'),
            'kegiatan' => $this->input->post('kegiatan'),
            'nilai_transaksi' => $total,
            'jenis_belanja_id' => $this->input->post('jenis_belanja_id'),
            'platform' => $this->input->post('platform'),
            'marketplace' => $this->input->post('marketplace'),
            'nama_toko' => $this->input->post('nama_toko'),
            'alamat_toko' => $this->input->post('alamat_toko'),
            'pembayaran' => $this->input->post('pembayaran'),
            'no_rekening' => $this->input->post('no_rekening'),
            'nama_bank' => $this->input->post('nama_bank')
        ]);
    }

    $this->session->set_flashdata('success', 'Pengeluaran berhasil disimpan!');
    redirect('pengeluaran');
  }

  // 🔹 Form Edit (manual / non-modal)
  public function edit($id)
  {
      $data['pengeluaran'] = $this->db->get_where('tb_pengeluaran', ['id' => $id])->row();
      if (!$data['pengeluaran']) {
          $this->session->set_flashdata('error', 'Data pengeluaran tidak ditemukan!');
          redirect('pengeluaran');
      }

      $data['kodering'] = $this->Kodering_model->get_all();
      $data['kategori_belanja'] = $this->Kategori_belanja_model->get_all();
      $data['sumber_anggaran'] = $this->Sumber_anggaran_model->get_all();

      $this->load->view('template/header');
      $this->load->view('template/sidebar_sekolah');
      $this->load->view('sekolah/pengeluaran_edit', $data);
      $this->load->view('template/footer');
  }
// 🔹 Proses Update Data Pengeluaran (non-AJAX)
public function update()
{
    // Pastikan request melalui POST
    if ($this->input->method() !== 'post') {
        show_error('Invalid request method', 405);
        return;
    }

    $id = $this->input->post('id');
    $pengeluaran = $this->db->get_where('tb_pengeluaran', ['id' => $id])->row();

    if (!$pengeluaran) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan!');
        redirect('pengeluaran');
    }

    // Upload bukti baru (jika ada)
    $bukti = $pengeluaran->bukti;
    if (!empty($_FILES['bukti']['name'])) {
        $config['upload_path'] = './uploads/bukti/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 2048;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('bukti')) {
            // hapus bukti lama
            if ($bukti && file_exists('./uploads/bukti/' . $bukti)) {
                unlink('./uploads/bukti/' . $bukti);
            }
            $bukti = $this->upload->data('file_name');
        }
    }

    // Update data
    $data = [
        'sumber_anggaran_id' => $this->input->post('sumber_anggaran_id'),
        'kegiatan' => $this->input->post('kegiatan'),
        'kodering_id' => $this->input->post('kodering_id'),
        'jenis_belanja_id' => $this->input->post('jenis_belanja_id'),
        'tanggal' => $this->input->post('tanggal'),
        'uraian' => $this->input->post('uraian'),
        'jumlah' => $this->input->post('jumlah'),
        'platform' => $this->input->post('platform'),
        'marketplace' => $this->input->post('marketplace'),
        'nama_toko' => $this->input->post('nama_toko'),
        'alamat_toko' => $this->input->post('alamat_toko'),
        'pembayaran' => $this->input->post('pembayaran'),
        'no_rekening' => $this->input->post('no_rekening'),
        'nama_bank' => $this->input->post('nama_bank'),
        'bukti' => $bukti
    ];

    $this->db->where('id', $id)->update('tb_pengeluaran', $data);

    // 🔁 Update total di rekap
    $total = $this->Pengeluaran_model->sum_by_invoice($pengeluaran->invoice_no);
    $this->Rekap_model->update_total($pengeluaran->invoice_no, $total);

    $this->session->set_flashdata('success', 'Data pengeluaran berhasil diperbarui!');
    redirect('pengeluaran');
}

  // 🔹 Edit cepat (modal AJAX)
  public function ajax_update()
  {
      if (!$this->input->is_ajax_request()) show_404();

      $id = $this->input->post('id');
      $data = [
          'kegiatan' => $this->input->post('kegiatan'),
          'tanggal' => $this->input->post('tanggal'),
          'uraian' => $this->input->post('uraian'),
          'jumlah' => $this->input->post('jumlah'),
          'platform' => $this->input->post('platform'),
          'pembayaran' => $this->input->post('pembayaran'),
          'nama_toko' => $this->input->post('nama_toko')
      ];

      $updated = $this->db->where('id', $id)->update('tb_pengeluaran', $data);

      if ($updated) {
          $pengeluaran = $this->db->get_where('tb_pengeluaran', ['id' => $id])->row();
          if ($pengeluaran) {
              $total = $this->Pengeluaran_model->sum_by_invoice($pengeluaran->invoice_no);
              $this->Rekap_model->update_total($pengeluaran->invoice_no, $total);
          }
          echo json_encode(['status' => true, 'message' => 'Data berhasil diperbarui!']);
      } else {
          echo json_encode(['status' => false, 'message' => 'Gagal memperbarui data!']);
      }
  }

  // 🔹 Hapus cepat (modal AJAX)
  public function hapus_ajax()
  {
      if (!$this->input->is_ajax_request()) show_404();

      $id = $this->input->post('id');
      $pengeluaran = $this->db->get_where('tb_pengeluaran', ['id' => $id])->row();

      if ($pengeluaran) {
          $this->db->delete('tb_pengeluaran', ['id' => $id]);
          $total = $this->Pengeluaran_model->sum_by_invoice($pengeluaran->invoice_no);

          if ($total > 0) {
              $this->Rekap_model->update_total($pengeluaran->invoice_no, $total);
          } else {
              $this->db->delete('tb_rekap_pembelanjaan', ['invoice_no' => $pengeluaran->invoice_no]);
          }

          echo json_encode(['status' => true, 'message' => 'Data berhasil dihapus dan rekap diperbarui!']);
      } else {
          echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
      }
  }

  // 🔹 Hapus satu baris manual (via link)
  public function hapus($id)
  {
      if ($this->Pengeluaran_model->delete($id)) {
          $this->session->set_flashdata('success', 'Data pengeluaran berhasil dihapus dan rekap diperbarui!');
      } else {
          $this->session->set_flashdata('error', 'Data tidak ditemukan!');
      }
      redirect('pengeluaran');
  }

  // 🔹 Hapus satu rekap dan semua pengeluaran terkait
  public function hapus_rekap($invoice_no)
  {
      if ($this->Rekap_model->cascade_delete($invoice_no)) {
          $this->session->set_flashdata('success', 'Rekap dan seluruh pengeluaran dengan invoice '.$invoice_no.' telah dihapus!');
      } else {
          $this->session->set_flashdata('error', 'Rekap tidak ditemukan!');
      }
      redirect('rekap');
  }
  // ==============================================================
  // 🔹 IMPORT EXCEL
  // ==============================================================
  public function import_excel()
  {
      $tahun_id = $this->session->userdata('tahun_id');
      $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
      $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

      if (!empty($_FILES['file_excel']['name'])) {
          $file = $_FILES['file_excel']['tmp_name'];
          $excel = PHPExcel_IOFactory::load($file);
          $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);

          $insert_data = array();
          $user_id = $this->session->userdata('user_id');
          $numrow = 1;

          foreach ($sheet as $row) {
              if ($numrow > 1) {
                  $sumber = trim($row['A']);
                  $kegiatan = trim($row['B']);
                  $invoice_no = trim($row['C']);
                  $tanggal = trim($row['D']);
                  $kodering_text = trim($row['E']);
                  $jenis_belanja = trim($row['F']);
                  $uraian = trim($row['G']);
                  $jumlah = preg_replace('/\D/', '', $row['H']);
                  $platform = trim($row['I']);
                  $marketplace = trim($row['J']); // kolom baru
                $nama_toko = trim($row['K']);
                $alamat_toko = trim($row['L']);
                $pembayaran = trim($row['M']);
                $no_rekening = trim($row['N']);
                $nama_bank = trim($row['O']);
                $tahun_excel = trim($row['P']);


                  $tahun_fix = !empty($tahun_excel) ? $tahun_excel : $tahun;
                  $invoice_no = $invoice_no ?: 'INV-' . $tahun_fix . '-' . str_pad($numrow, 3, '0', STR_PAD_LEFT);

                  $sumber_row = $this->db->like('nama', $sumber)->get('tb_sumber_anggaran')->row();
                  $sumber_id = $sumber_row ? $sumber_row->id : null;

                  $kode_bersih = explode('-', $kodering_text)[0];
                  $kode_bersih = trim($kode_bersih);
                  $kodering_row = $this->db->like('kode', $kode_bersih)->get('tb_kodering')->row();
                  $kodering_id = $kodering_row ? $kodering_row->id : null;

                  $jenis_row = $this->db->like('nama', $jenis_belanja)->get('tb_kategori_belanja')->row();
                  $jenis_id = $jenis_row ? $jenis_row->id : null;

                  if (empty($kegiatan) || empty($tanggal) || empty($jumlah)) {
                      $numrow++;
                      continue;
                  }

                  $insert_data[] = array(
                      'user_id' => $user_id,
                      'sekolah_id' => $user_id,
                      'sumber_anggaran_id' => $sumber_id,
                      'kegiatan' => $kegiatan,
                      'invoice_no' => $invoice_no,
                      'tanggal' => $tanggal,
                      'kodering_id' => $kodering_id,
                      'jenis_belanja_id' => $jenis_id,
                      'uraian' => $uraian,
                      'jumlah' => $jumlah ?: 0,
                      'platform' => $platform ?: 'Non_SIPLAH',
                      'marketplace' => $marketplace,
                      'nama_toko' => $nama_toko,
                      'alamat_toko' => $alamat_toko,
                      'pembayaran' => $pembayaran ?: 'Tunai',
                      'no_rekening' => $no_rekening,
                      'nama_bank' => $nama_bank,
                      'status' => 'Menunggu',
                      'tahun_anggaran' => $tahun_fix,
                      'tahun' => $tahun_fix
                  );
              }
              $numrow++;
          }

          if (!empty($insert_data)) {
              $this->db->insert_batch('tb_pengeluaran', $insert_data);
              $this->Pengeluaran_model->sync_rekap();
              $this->session->set_flashdata('success', count($insert_data) . ' data berhasil diimpor untuk tahun ' . $tahun . '!');
          } else {
              $this->session->set_flashdata('error', 'Tidak ada data valid yang diimpor!');
          }
      } else {
          $this->session->set_flashdata('error', 'File Excel belum dipilih!');
      }
      redirect('pengeluaran');
  }

  // ==============================================================
  // 🔹 DOWNLOAD TEMPLATE
  // ==============================================================
 // 🔹 Download template Excel (lengkap dengan kolom Tahun, dropdown, dan Petunjuk)
public function download_template()
{
    $tahun_aktif = $this->Tahun_model->get_aktif();
    $tahun = $tahun_aktif ? $tahun_aktif->tahun : date('Y');

    $this->load->library('PHPExcel_lib');
    $this->load->model(['Kodering_model', 'Kategori_belanja_model', 'Sumber_anggaran_model']);

    $excel = new PHPExcel();
    $excel->getProperties()->setCreator('Sipelakor')
           ->setTitle('Template Import Pengeluaran Sekolah');

    // Sheet 1: Template utama
    $sheet = $excel->setActiveSheetIndex(0);
    $sheet->setTitle('Template Pengeluaran');

    $headers = [
      "Sumber Anggaran", "Kegiatan", "No Invoice / Virtual Account",
      "Tanggal (YYYY-MM-DD)", "Kodering (kode - nama)", "Jenis Belanja",
      "Uraian", "Jumlah (Rp)", "Platform (SIPLAH / Non_SIPLAH)", "Marketplace / Mitra SIPLAH",
      "Nama Toko / Penyedia", "Alamat Toko", "Pembayaran (Tunai / Non-Tunai)",
      "No Rekening", "Nama Bank", "Tahun"
    ];

    $col = 'A';
    foreach ($headers as $h) {
        $sheet->setCellValue($col . '1', $h);
        $sheet->getStyle($col . '1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]],
            'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D9E1F2']]
        ]);
        $sheet->getColumnDimension($col)->setAutoSize(true);
        $col++;
    }

    // Contoh data (baris 2)
    $sample = [
      'BOS Reguler',
      'Kegiatan Sumatif',
      'INV-'.$tahun.'-001',
      $tahun.'-01-01',
      '0123456 - Nama Kodering',
      'Belanja Barang Habis Pakai',
      'ATK Ujian',
      '150000',
      'Non_SIPLAH',
      'Blibli',
      'Toko Maju Jaya',
      'Jl. Merdeka No.12',
      'Tunai',
      '1234567890',
      'Bank BRI',
      $tahun
    ];
    $sheet->fromArray([$sample], NULL, 'A2');

    // Sheet 2: Data Kodering
    $kodering_list = $this->Kodering_model->get_all();
    $sheet2 = $excel->createSheet();
    $sheet2->setTitle('DataKodering');
    $r = 1;
    foreach ($kodering_list as $k) {
        $sheet2->setCellValue('A' . $r, "{$k->kode} - {$k->nama}");
        $r++;
    }

    // Sheet 3: Jenis Belanja
    $sheet3 = $excel->createSheet();
    $sheet3->setTitle('JenisBelanja');
    $jenis_list = $this->Kategori_belanja_model->get_all();
    $j = 1;
    foreach ($jenis_list as $jb) {
        $sheet3->setCellValue('A' . $j, $jb->nama);
        $j++;
    }

    // ✅ Sheet 4: Data Sumber Anggaran
    $sheetSA = $excel->createSheet();
    $sheetSA->setTitle('DataSumberAnggaran');
    $sumber_list = $this->Sumber_anggaran_model->get_all();
    $s = 1;
    foreach ($sumber_list as $sa) {
        $sheetSA->setCellValue('A' . $s, $sa->nama);
        $s++;
    }

    // Sheet 5: Petunjuk Pengisian
    $sheet4 = $excel->createSheet();
    $sheet4->setTitle('Petunjuk');
    $sheet4->setCellValue('A1', 'PETUNJUK PENGISIAN TEMPLATE PENGELUARAN');
    $sheet4->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet4->getColumnDimension('A')->setWidth(120);

    $petunjuk = [
        "1. Jangan ubah urutan kolom di sheet 'Template Pengeluaran'.",
        "2. Isi mulai baris ke-2. Baris pertama adalah header.",
        "3. Format tanggal wajib: YYYY-MM-DD (contoh: {$tahun}-01-15).",
        "4. Kolom 'Kodering', 'Jenis Belanja', 'Sumber Anggaran', dan 'Platform' memiliki dropdown otomatis.",
        "5. Kolom 'Jumlah' isi angka tanpa Rp, titik, atau koma.",
        "6. Kolom 'Tahun' akan otomatis terisi tahun aktif ({$tahun}).",
        "7. Platform hanya: SIPLAH / Non_SIPLAH.",
        "8. Pembayaran hanya: Tunai / Non-Tunai.",
        "9. Kolom rekening & bank wajib jika pembayaran Non-Tunai.",
        "10. Setelah isi semua, simpan & upload kembali lewat menu Pengeluaran Sekolah."
    ];
    $row = 3;
    foreach ($petunjuk as $p) {
        $sheet4->setCellValue('A' . $row, $p);
        $row += 2;
    }

    // 🔒 Sembunyikan sheet data
    $excel->getSheetByName('DataSumberAnggaran')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
    $excel->getSheetByName('DataKodering')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
    $excel->getSheetByName('JenisBelanja')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    // Dropdown Kodering (kolom E)
    $validation_kodering = $sheet->getCell('E2')->getDataValidation();
    $validation_kodering->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $validation_kodering->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
    $validation_kodering->setAllowBlank(true);
    $validation_kodering->setShowDropDown(true);
    $validation_kodering->setFormula1('=DataKodering!$A$1:$A$' . ($r - 1));
    for ($i = 2; $i <= 500; $i++) {
        $sheet->getCell("E$i")->setDataValidation(clone $validation_kodering);
    }

    // Dropdown Jenis Belanja (kolom F)
    $validation_jb = $sheet->getCell('F2')->getDataValidation();
    $validation_jb->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $validation_jb->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
    $validation_jb->setAllowBlank(true);
    $validation_jb->setShowDropDown(true);
    $validation_jb->setFormula1('=JenisBelanja!$A$1:$A$' . ($j - 1));
    for ($i = 2; $i <= 500; $i++) {
        $sheet->getCell("F$i")->setDataValidation(clone $validation_jb);
    }

    // ✅ Dropdown Sumber Anggaran (kolom A)
    $validation_sa = $sheet->getCell('A2')->getDataValidation();
    $validation_sa->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $validation_sa->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
    $validation_sa->setAllowBlank(true);
    $validation_sa->setShowDropDown(true);
    $validation_sa->setFormula1('=DataSumberAnggaran!$A$1:$A$' . ($s - 1));
    for ($i = 2; $i <= 500; $i++) {
        $sheet->getCell("A$i")->setDataValidation(clone $validation_sa);
    }

    // ✅ Dropdown Platform (kolom I: SIPLAH / Non_SIPLAH)
    $validation_platform = $sheet->getCell('I2')->getDataValidation();
    $validation_platform->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $validation_platform->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
    $validation_platform->setAllowBlank(true);
    $validation_platform->setShowDropDown(true);
    $validation_platform->setFormula1('"SIPLAH,Non_SIPLAH"');
    for ($i = 2; $i <= 500; $i++) {
        $sheet->getCell("I$i")->setDataValidation(clone $validation_platform);
    }

    // ✅ Dropdown Marketplace (kolom J, tetap seperti semula)
    for ($i = 2; $i <= 500; $i++) {
        $sheet->getCell("J$i")->getDataValidation()
              ->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
              ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP)
              ->setAllowBlank(true)
              ->setShowDropDown(true)
              ->setFormula1('"Blibli,Toko Ladang,Pesona Edu,Belanja Sekolah,Lainnya"');
    }

    // Output file Excel
    if (ob_get_length()) { @ob_end_clean(); }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Template_Pengeluaran_Sekolah.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save('php://output');
    exit;
}

}
