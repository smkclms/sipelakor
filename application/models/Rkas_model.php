<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rkas_model extends CI_Model {

  public function get_by_sekolah($sekolah_id, $tahun_id) {
    $this->db->where('sekolah_id', $sekolah_id);
    $this->db->where('tahun_id', $tahun_id);
    $this->db->order_by('id_rkas', 'DESC');
    return $this->db->get('rkas')->result();
  }

  public function insert($data) {
    return $this->db->insert('rkas', $data);
  }

  public function delete($id) {
    $this->db->where('id_rkas', $id);
    return $this->db->delete('rkas');
  }
}
