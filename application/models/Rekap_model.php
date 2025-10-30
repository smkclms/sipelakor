<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_model extends CI_Model {

    protected $table = 'tb_rekap_pembelanjaan';

    public function get($invoice_no)
{
    $this->db->select('r.*, kb.nama AS nama_jenis_belanja');
    $this->db->from('tb_rekap_pembelanjaan r');
    $this->db->join('tb_kategori_belanja kb', 'r.jenis_belanja_id = kb.id', 'left');
    $this->db->where('r.invoice_no', $invoice_no);
    return $this->db->get()->row();
}


    /**
     * ✅ Insert otomatis isi sumber_anggaran_id (fallback dari tb_pengeluaran)
     */
    public function insert($data){
        // Jika sumber_anggaran_id kosong, cari dari tb_pengeluaran
        if (empty($data['sumber_anggaran_id']) && !empty($data['invoice_no'])) {
            $pengeluaran = $this->db
                ->select('sumber_anggaran_id')
                ->where('invoice_no', $data['invoice_no'])
                ->order_by('id', 'DESC')
                ->get('tb_pengeluaran')
                ->row();

            if ($pengeluaran && !empty($pengeluaran->sumber_anggaran_id)) {
                $data['sumber_anggaran_id'] = $pengeluaran->sumber_anggaran_id;
            }
        }

        return $this->db->insert($this->table, $data);
    }

    public function update_total($invoice_no, $total){
    $this->db->where('invoice_no', $invoice_no);
    $this->db->update($this->table, ['nilai_transaksi' => $total]);

    // 🔹 Tambahkan sinkron marketplace
    $this->update_marketplace($invoice_no);

    return true;
}


    // 🔹 Hapus 1 rekap + seluruh data pengeluaran dengan invoice yang sama
    public function cascade_delete($invoice_no){
        $this->db->where('invoice_no', $invoice_no)->delete('tb_pengeluaran');
        return $this->db->where('invoice_no', $invoice_no)->delete($this->table);
    }

    // 🔹 Daftar rekap untuk admin (semua sekolah)
    public function get_all_by_admin(){
        $this->db->select('r.*, u.nama as nama_sekolah, kb.nama as jenis_belanja');
        $this->db->from('tb_rekap_pembelanjaan r');
        $this->db->join('tb_user u', 'r.sekolah_id = u.id', 'left');
        $this->db->join('tb_kategori_belanja kb', 'r.jenis_belanja_id = kb.id', 'left');
        $this->db->order_by('r.tanggal','DESC');
        return $this->db->get()->result();
    }

    // 🔹 Daftar rekap admin berdasarkan tahun
    public function get_all_by_admin_tahun($tahun_id = null)
    {
        if ($tahun_id === null) {
            $tahun_id = $this->session->userdata('tahun_id');
        }

        // Ambil nilai tahun angka
        $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
        $tahun = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

        $this->db->select('r.*, u.nama as nama_sekolah, kb.nama as jenis_belanja');
        $this->db->from('tb_rekap_pembelanjaan r');
        $this->db->join('tb_user u', 'r.sekolah_id = u.id', 'left');
        $this->db->join('tb_kategori_belanja kb', 'r.jenis_belanja_id = kb.id', 'left');
        $this->db->where('YEAR(r.tanggal)', $tahun);
        $this->db->order_by('r.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // 🔹 Daftar rekap untuk sekolah (user)
    public function get_by_sekolah($sekolah_id, $tahun_id = null)
{
    // 🔹 Ambil tahun aktif secara terpisah
    $tahun = null;
    if ($tahun_id) {
        $tahun_row = $this->db->query("
            SELECT tahun FROM tb_tahun_anggaran WHERE id = ?
        ", [$tahun_id])->row();
        if ($tahun_row) {
            $tahun = $tahun_row->tahun;
        }
    }

    // 🔹 Query utama
    $this->db->select('r.*, j.nama AS jenis_belanja, t.tahun AS tahun_anggaran');
    $this->db->from('tb_rekap_pembelanjaan r');
    $this->db->join('tb_kategori_belanja j', 'r.jenis_belanja_id = j.id', 'left');
    $this->db->join('tb_tahun_anggaran t', 'YEAR(r.tanggal) = t.tahun', 'left');
    $this->db->where('r.sekolah_id', $sekolah_id);

    if ($tahun) {
        $this->db->where('YEAR(r.tanggal)', $tahun);
    }

    $this->db->order_by('r.tanggal', 'DESC');
    return $this->db->get()->result();
}
public function update_marketplace($invoice_no)
{
    // Ambil semua marketplace unik berdasarkan invoice
    $this->db->select('DISTINCT(marketplace)');
    $this->db->where('invoice_no', $invoice_no);
    $query = $this->db->get('tb_pengeluaran');

    $marketplaces = array();
    foreach ($query->result() as $row) {
        if (!empty($row->marketplace)) {
            $marketplaces[] = $row->marketplace;
        }
    }

    // Gabungkan jadi string, atau null kalau kosong
    $marketplace_str = !empty($marketplaces) ? implode(', ', array_unique($marketplaces)) : NULL;

    // Update kolom marketplace di tb_rekap_pembelanjaan
    $this->db->where('invoice_no', $invoice_no);
    $this->db->update($this->table, ['marketplace' => $marketplace_str]);
}


}
