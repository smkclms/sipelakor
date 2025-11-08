<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arkas_model extends CI_Model {

  public function get_by_sekolah($sekolah_id) {
    return $this->db->where('sekolah_id', $sekolah_id)
                    ->order_by('tanggal_upload', 'DESC')
                    ->get('rkas_upload')
                    ->result();
  }

  public function get_by_id($id) {
    return $this->db->get_where('rkas_upload', ['id_upload' => $id])->row();
  }

  public function insert($data) {
    return $this->db->insert('rkas_upload', $data);
  }

  public function delete($id) {
    $this->db->where('id_upload', $id);
    return $this->db->delete('rkas_upload');
  }
  public function count_by_sekolah($sekolah_id) {
  return $this->db->where('sekolah_id', $sekolah_id)
                  ->count_all_results('rkas_upload');
}
public function get_all_with_school() {
  $this->db->select('a.*, u.nama AS nama_sekolah');
  $this->db->from('rkas_upload a');
  $this->db->join('tb_user u', 'u.id = a.sekolah_id', 'left');
  $this->db->order_by('u.nama ASC');
  $this->db->order_by('a.tanggal_upload DESC');
  return $this->db->get()->result();
}

}
