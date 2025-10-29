<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_model extends CI_Model {

    protected $table = 'tb_tahun_anggaran';

    // 🔹 Ambil tahun aktif
    public function get_aktif() {
        return $this->db->where('aktif', 1)
                        ->get($this->table)
                        ->row();
    }

    // 🔹 Ambil semua tahun
    public function get_all() {
        return $this->db->order_by('tahun', 'DESC')->get($this->table)->result();
    }

    // 🔹 Set tahun aktif (pastikan hanya 1)
    public function set_aktif($id) {
        $this->db->update($this->table, ['aktif' => 0]); // matikan semua dulu
        return $this->db->where('id', $id)->update($this->table, ['aktif' => 1]);
    }
    public function get_by_id($id) {
    return $this->db->get_where($this->table, ['id' => $id])->row();
}

}
