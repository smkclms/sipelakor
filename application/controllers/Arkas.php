<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arkas extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Arkas_model', 'Tahun_model', 'User_model']);
    $this->load->library(['upload', 'PHPExcel_lib']);

    // Cek login sekolah
    if (!$this->session->userdata('sekolah_id')) {
      redirect('auth');
    }
  }

  public function index() {
    $data['judul'] = 'Upload RKAS (ARKAS)';
    $sekolah_id = $this->session->userdata('sekolah_id');
    $data['uploads'] = $this->Arkas_model->get_by_sekolah($sekolah_id);

    // Jika sudah ada file terakhir, tampilkan preview 10 baris
    if (!empty($data['uploads'])) {
      $latest = $data['uploads'][0];
      $data['preview'] = $this->_preview_excel($latest->file_path);
    } else {
      $data['preview'] = [];
    }

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_sekolah', $data);
    $this->load->view('sekolah/arkas/index', $data);
    $this->load->view('template/footer');
  }

  public function upload() {
    $sekolah_id = $this->session->userdata('sekolah_id');
    $tahun_aktif = $this->Tahun_model->get_aktif();

    // Ambil data sekolah dari User_model
    $sekolah = $this->User_model->get_by_id($sekolah_id);
    $nama_sekolah = $sekolah ? preg_replace('/[^A-Za-z0-9 \-()]/', '', $sekolah->nama) : 'Sekolah';

    // Tentukan revisi ke berapa
    $count_upload = $this->Arkas_model->count_by_sekolah($sekolah_id);
    $versi = $count_upload + 1;
    $nama_file_simpan = $nama_sekolah . " (revisi{$versi})";

    // Ambil tahun aktif (fallback ke 0)
    $tahun_id = 0;
    if ($tahun_aktif) {
      if (isset($tahun_aktif->id_tahun)) $tahun_id = $tahun_aktif->id_tahun;
      elseif (isset($tahun_aktif->id)) $tahun_id = $tahun_aktif->id;
      elseif (isset($tahun_aktif->tahun_id)) $tahun_id = $tahun_aktif->tahun_id;
    }

    $config['upload_path']   = './uploads/arkas/';
    $config['allowed_types'] = 'xls|xlsx';
    $config['max_size']      = 4096; // 4MB
    $config['file_name']     = $nama_file_simpan;

    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, true);
    }

    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file_arkas')) {
      $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
    } else {
      $fileData = $this->upload->data();

      $saveData = array(
        'sekolah_id' => $sekolah_id,
        'tahun_id'   => $tahun_id,
        'nama_file'  => $fileData['orig_name'],
        'file_path'  => 'uploads/arkas/' . $fileData['file_name'],
        'tanggal_upload' => date('Y-m-d H:i:s')
      );

      $this->Arkas_model->insert($saveData);
      $this->session->set_flashdata('success', 'File RKAS berhasil diunggah sebagai ' . $fileData['file_name']);
    }

    redirect('arkas');
  }

  public function hapus($id) {
    $file = $this->Arkas_model->get_by_id($id);
    if ($file) {
      $fullpath = FCPATH . $file->file_path;
      if (file_exists($fullpath)) {
        @unlink($fullpath);
      }
      $this->Arkas_model->delete($id);
      $this->session->set_flashdata('success', 'File berhasil dihapus.');
    }
    redirect('arkas');
  }
public function preview_ajax($id) {
  $file = $this->Arkas_model->get_by_id($id);
  if (!$file) {
    echo '<div class="alert alert-danger m-2">File tidak ditemukan.</div>';
    return;
  }

  $full_path = FCPATH . $file->file_path;
  if (!file_exists($full_path)) {
    echo '<div class="alert alert-warning m-2">File tidak ditemukan di server.</div>';
    return;
  }

  try {
    $excel = PHPExcel_IOFactory::load($full_path);
    $sheet = $excel->getSheet(0);
    $highestRow = $sheet->getHighestRow();

    $highestColumn = $sheet->getHighestColumn();
    $rows = $sheet->rangeToArray('A1:' . $highestColumn . $highestRow, NULL, TRUE, FALSE);

    echo '<div class="table-responsive"><table class="table table-sm table-bordered">';
    foreach ($rows as $i => $row) {
      echo '<tr>';
      foreach ($row as $cell) {
        echo '<td>' . htmlspecialchars($cell) . '</td>';
      }
      echo '</tr>';
    }
    echo '</table></div>';
  } catch (Exception $e) {
    echo '<div class="alert alert-danger m-2">Gagal memuat file Excel.</div>';
  }
}

  // ==== PRIVATE PREVIEW ====
  private function _preview_excel($path) {
    $preview = array();
    $full_path = FCPATH . $path;
    if (!file_exists($full_path)) return $preview;

    try {
      $excel = PHPExcel_IOFactory::load($full_path);
      $sheet = $excel->getSheet(0);
      $highestRow = min(10, $sheet->getHighestRow());
      $highestColumn = $sheet->getHighestColumn();
      $header = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
      $rows = $sheet->rangeToArray('A2:' . $highestColumn . $highestRow, NULL, TRUE, FALSE);
      $preview = array_merge($header, $rows);
    } catch (Exception $e) {
      $preview = array();
    }

    return $preview;
  }
}
