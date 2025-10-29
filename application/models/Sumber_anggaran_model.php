<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sumber_anggaran_model extends CI_Model {

  protected $table = 'tb_sumber_anggaran';

  public function get_all(){
    return $this->db->order_by('nama', 'ASC')->get($this->table)->result();
  }
  public function index() {
    $data['title'] = 'Anggaran Sekolah';
    $sekolah_id = $this->session->userdata('sekolah_id');
    $tahun_id   = $this->session->userdata('tahun_id'); // tahun aktif dari login

    $data['anggaran'] = $this->Anggaran_model->get_by_sekolah($sekolah_id, $tahun_id);
    
    // 🔹 Tambahkan ini
    $data['sumber'] = $this->Sumber_anggaran_model->get_all();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_sekolah', $data);
    $this->load->view('sekolah/anggaran_list', $data);
    $this->load->view('template/footer');
}

}
