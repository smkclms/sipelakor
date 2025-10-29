<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran_model extends CI_Model {

    private $table = 'tb_anggaran_sekolah';

    public function get_by_sekolah($sekolah_id, $tahun_id = null)
{
    if ($tahun_id === null) {
        $tahun_id = $this->session->userdata('tahun_id');
    }

    $this->db->select('a.*, s.nama as nama_sumber');
    $this->db->from('tb_anggaran_sekolah a');
    $this->db->join('tb_sumber_anggaran s', 's.id = a.sumber_id', 'left');
    $this->db->where('a.sekolah_id', $sekolah_id);
    $this->db->where('a.tahun_id', $tahun_id);
    return $this->db->get()->result();
}



    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    
}
