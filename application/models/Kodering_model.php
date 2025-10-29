<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kodering_model extends CI_Model {

  protected $table = 'tb_kodering';

  public function get_all(){
    return $this->db->select('k.*, kb.nama as kategori')
                    ->from('tb_kodering k')
                    ->join('tb_kategori_belanja kb', 'k.kategori_id = kb.id', 'left')
                    ->order_by('k.kode', 'ASC')
                    ->get()
                    ->result();
  }

  public function get($id){
    return $this->db->where('id', $id)->get($this->table)->row();
  }

  public function insert($data) {
    return $this->db->insert('tb_kodering', $data);
}

  public function update($id, $data){
    return $this->db->where('id', $id)->update($this->table, $data);
  }

  public function delete($id){
    return $this->db->where('id', $id)->delete($this->table);
  }
  
}
