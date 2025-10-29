<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Pengeluaran_model', 'Tahun_model']);
    $this->load->library('session');

    if (!$this->session->userdata('logged_in') || strtolower($this->session->userdata('role')) != 'admin') {
      redirect('auth/login');
    }
  }

  public function index() {
    // Ambil tahun_id dari session login
    $tahun_id = $this->session->userdata('tahun_id');

    // Dapatkan nilai tahun dari tabel tahun_anggaran
    $tahun_row = $this->Tahun_model->get_by_id($tahun_id);
    $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

    $data['tahun'] = $tahun;
    $data['pengeluaran'] = $this->Pengeluaran_model->get_all_with_sekolah($tahun_id);

    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/pengeluaran_list', $data);
    $this->load->view('template/footer');
  }

  public function laporan() {
    $tahun_id = $this->session->userdata('tahun_id');

    $tahun_row = $this->Tahun_model->get_by_id($tahun_id);
    $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

    $data['tahun'] = $tahun;
    $data['pengeluaran'] = $this->Pengeluaran_model->get_all_with_sekolah($tahun_id);

    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/pengeluaran_laporan', $data);
    $this->load->view('template/footer');
  }
}
