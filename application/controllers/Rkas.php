<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rkas extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Rkas_model', 'Tahun_model']);
    $this->load->library('form_validation');

    // ✅ Cek login sama seperti Anggaran
    if (!$this->session->userdata('sekolah_id')) {
        redirect('auth');
    }
}


 public function index() {
    $sekolah_id = $this->session->userdata('sekolah_id');
    $tahun_aktif = $this->Tahun_model->get_aktif();
    $tahun_id = 0;
    if ($tahun_aktif) {
        if (isset($tahun_aktif->id_tahun)) {
            $tahun_id = $tahun_aktif->id_tahun;
        } elseif (isset($tahun_aktif->id)) {
            $tahun_id = $tahun_aktif->id;
        } elseif (isset($tahun_aktif->tahun_id)) {
            $tahun_id = $tahun_aktif->tahun_id;
        }
    }

    $data['judul'] = 'Rencana Kegiatan & Anggaran Sekolah (RKAS)';
    $data['rkas']  = $this->Rkas_model->get_by_sekolah($sekolah_id, $tahun_id);

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_sekolah', $data);
    $this->load->view('sekolah/rkas/index', $data);
    $this->load->view('template/footer');
}


  public function tambah() {
    $data['judul'] = 'Tambah Rencana Kegiatan & Anggaran';
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_sekolah');
    $this->load->view('sekolah/rkas/form', $data);
    $this->load->view('template/footer');
  }

  public function simpan() {
    $this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'required|trim');
    $this->form_validation->set_rules('sumber_anggaran', 'Sumber Anggaran', 'required|trim');
    $this->form_validation->set_rules('total_rencana', 'Total Rencana', 'required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $this->tambah();
    } else {
      $sekolah_id = $this->session->userdata('sekolah_id');
      $tahun_aktif = $this->Tahun_model->get_aktif();

      $data = array(
        'sekolah_id' => $sekolah_id,
        'tahun_id' => $tahun_aktif ? $tahun_aktif->id_tahun : 0,
        'nama_kegiatan' => $this->input->post('nama_kegiatan', TRUE),
        'sumber_anggaran' => $this->input->post('sumber_anggaran', TRUE),
        'uraian' => $this->input->post('uraian', TRUE),
        'total_rencana' => str_replace(',', '', $this->input->post('total_rencana')),
        'tanggal_input' => date('Y-m-d H:i:s')
      );

      $this->Rkas_model->insert($data);
      $this->session->set_flashdata('success', 'Rencana kegiatan berhasil disimpan.');
      redirect('rkas');
    }
  }

  public function hapus($id) {
    $this->Rkas_model->delete($id);
    $this->session->set_flashdata('success', 'Data berhasil dihapus.');
    redirect('rkas');
  }
}
