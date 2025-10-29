<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_belanja_model extends CI_Model {

  protected $table = 'tb_kategori_belanja';

  // Ambil semua kategori
  public function get_all(){
    return $this->db->order_by('id', 'ASC')->get($this->table)->result();
  }

  // Ambil kategori berdasarkan ID
  public function get($id){
    return $this->db->where('id', $id)->get($this->table)->row();
  }

  // Tambah kategori baru
  public function insert($data){
    return $this->db->insert($this->table, $data);
  }

  // Update kategori
  public function update($id, $data){
    return $this->db->where('id', $id)->update($this->table, $data);
  }

  // Hapus kategori
  public function delete($id){
    return $this->db->where('id', $id)->delete($this->table);
  }
}
