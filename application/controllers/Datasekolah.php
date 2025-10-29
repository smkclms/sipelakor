<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datasekolah extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model(['User_model', 'Anggaran_model', 'Rekap_model', 'Tahun_model', 'Jenjang_model']);
    $this->load->library('session');

    if (!$this->session->userdata('logged_in') || strtolower($this->session->userdata('role')) != 'admin') {
      redirect('auth/login');
    }
  }

  public function index() {
    // Ambil tahun_id dari session login
    $tahun_id = $this->session->userdata('tahun_id');
    $tahun_row = $this->Tahun_model->get_by_id($tahun_id);
    $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

    $data['title'] = 'Data Sekolah & Anggaran';
    $data['tahun'] = $tahun;

    // 🔹 Ambil jenjang filter (dari dropdown GET)
    $jenjang_id = $this->input->get('jenjang_id');

    // 🔹 Ambil semua jenjang untuk dropdown
    $data['jenjang'] = $this->Jenjang_model->get_all();
    $data['selected_jenjang'] = $jenjang_id;

    // 🔹 Ambil sekolah (filtered jika ada jenjang dipilih)
    if (!empty($jenjang_id)) {
      $sekolah = $this->User_model->get_by_jenjang($jenjang_id);
    } else {
      $sekolah = $this->User_model->get_all_sekolah();
    }

    $result = array();
    foreach ($sekolah as $s) {
      // Ambil anggaran per sekolah untuk tahun ini
      $anggaran = $this->db
        ->select('a.*, sa.nama as nama_sumber')
        ->from('tb_anggaran_sekolah a')
        ->join('tb_sumber_anggaran sa', 'a.sumber_id = sa.id', 'left')
        ->where('a.sekolah_id', $s->id)
        ->where('a.tahun_id', $tahun_id)
        ->get()->result();

      // Hitung total penggunaan (rekap) hanya untuk tahun ini
      $penggunaan = $this->db
        ->select_sum('nilai_transaksi', 'total')
        ->where('sekolah_id', $s->id)
        ->where('YEAR(tanggal)', $tahun)
        ->get('tb_rekap_pembelanjaan')
        ->row()->total;

      $s->anggaran = $anggaran;
      $s->total_penggunaan = $penggunaan ? $penggunaan : 0;

      $result[] = $s;
    }

    $data['sekolah'] = $result;

    // Load view
    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/data_sekolah', $data);
    $this->load->view('template/footer');
  }
}
