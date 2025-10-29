<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
  protected $table = 'tb_user';

  public function get_by_kontrol($no_kontrol){
    return $this->db->where('no_kontrol',$no_kontrol)->get($this->table)->row();
  }

  public function get($id){
    return $this->db->where('id',$id)->get($this->table)->row();
  }

  public function create($data){
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($id, $data){
    return $this->db->where('id',$id)->update($this->table, $data);
  }
public function delete($id){
  return $this->db->delete($this->table, ['id' => $id]);
}

  public function list_sekolah_by_cabang($cabang_id){
    return $this->db->where('cabang_id',$cabang_id)->where('role','sekolah')->get($this->table)->result();
  }
  public function get_all_schools_with_anggaran() {
    $this->db->select('u.id, u.nama, u.no_kontrol, j.nama AS jenjang');
$this->db->from('tb_user u');
$this->db->join('tb_jenjang j', 'u.jenjang_id = j.id', 'left');
$this->db->where('u.role', 'sekolah');

    $schools = $this->db->get()->result();

    $result = [];

    foreach ($schools as $s) {
        // Ambil semua sumber anggaran untuk sekolah ini
        $anggaran = $this->db->select('a.*, sa.nama AS nama_sumber')
            ->from('tb_anggaran_sekolah a')
            ->join('tb_sumber_anggaran sa', 'a.sumber_id = sa.id', 'left')
            ->where('a.sekolah_id', $s->id)
            ->get()
            ->result();

        // Ambil total penggunaan dari rekap
        $row = $this->db->select_sum('nilai_transaksi', 'total')
            ->from('tb_rekap_pembelanjaan')
            ->where('sekolah_id', $s->id)
            ->get()
            ->row();
        $penggunaan = ($row && isset($row->total)) ? $row->total : 0;

        $s->anggaran = $anggaran;
        $s->total_penggunaan = $penggunaan;

        $result[] = $s;
    }

    return $result;
}
public function get_all_sekolah()
{
  return $this->db->where('role', 'sekolah')
                  ->order_by('nama', 'ASC')
                  ->get('tb_user')
                  ->result();
}

public function get_by_jenjang($jenjang_id)
{
  return $this->db->where('role', 'sekolah')
                  ->where('jenjang_id', $jenjang_id)
                  ->order_by('nama', 'ASC')
                  ->get('tb_user')
                  ->result();
}


}
