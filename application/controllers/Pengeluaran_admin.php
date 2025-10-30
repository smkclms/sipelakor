<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Pengeluaran_model', 'Tahun_model', 'User_model']);
    $this->load->library(['session','pagination']);
    if (!$this->session->userdata('logged_in') || strtolower($this->session->userdata('role')) != 'admin') {
      redirect('auth/login');
    }
  }

  public function index() {
    $tahun_id = $this->session->userdata('tahun_id');
    $tahun_row = $this->Tahun_model->get_by_id($tahun_id);
    $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

    // 🔍 Ambil filter dari GET
    $status = $this->input->get('status');
    $jenjang = $this->input->get('jenjang');
    $sekolah_id = $this->input->get('sekolah_id');

    // Pagination config
    $config['base_url'] = site_url('pengeluaran_admin/index');
    $config['total_rows'] = $this->Pengeluaran_model->count_all_filtered($tahun_id, $jenjang, $sekolah_id, $status);
    $config['per_page'] = 15;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $config['reuse_query_string'] = TRUE;
    $this->pagination->initialize($config);

    $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;

    $data['tahun'] = $tahun;
    $data['jenjang'] = $jenjang;
    $data['sekolah_id'] = $sekolah_id;
    $data['status'] = $status;
    $data['pagination'] = $this->pagination->create_links();
    $data['sekolah'] = $this->User_model->get_all_sekolah();
    $data['pengeluaran'] = $this->Pengeluaran_model->get_all_with_filter($tahun_id, $jenjang, $sekolah_id, $config['per_page'], $page, $status);

    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/pengeluaran_list', $data);
    $this->load->view('template/footer');
  }

  // ✅ Verifikasi massal
  public function verifikasi_massal() {
    $ids = $this->input->post('ids');
    $aksi = $this->input->post('aksi');
    if ($ids && in_array($aksi, ['Disetujui', 'Ditolak'])) {
      foreach ($ids as $id) {
        $this->Pengeluaran_model->update_status($id, $aksi);
      }
      $this->session->set_flashdata('success', 'Berhasil memperbarui status beberapa pengeluaran.');
    } else {
      $this->session->set_flashdata('warning', 'Tidak ada data yang dipilih.');
    }
    redirect('pengeluaran_admin');
  }

  public function setujui($id) {
    $this->Pengeluaran_model->update_status($id, 'Disetujui');
    $this->session->set_flashdata('success', '✅ Pengeluaran telah disetujui.');
    redirect('pengeluaran_admin');
  }

  public function tolak($id) {
    $this->Pengeluaran_model->update_status($id, 'Ditolak');
    $this->session->set_flashdata('warning', '❌ Pengeluaran telah ditolak.');
    redirect('pengeluaran_admin');
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
