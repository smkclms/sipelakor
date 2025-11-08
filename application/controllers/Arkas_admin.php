<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arkas_admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Arkas_model', 'User_model']);
    $this->load->library('PHPExcel_lib'); // <== tambahkan baris ini
    if (!$this->session->userdata('role') || $this->session->userdata('role') != 'admin') {
      redirect('auth');
    }
  }

  public function index() {
    $data['judul'] = 'Rencana Anggaran Sekolah (ARKAS)';
    $data['uploads'] = $this->Arkas_model->get_all_with_school();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_admin', $data);
    $this->load->view('admin/arkas/index', $data);
    $this->load->view('template/footer');
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
    $inputFileType = PHPExcel_IOFactory::identify($full_path);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $excel = $objReader->load($full_path);

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
    echo '<div class="alert alert-danger m-2">Gagal memuat file Excel: '.$e->getMessage().'</div>';
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
