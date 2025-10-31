<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_model extends CI_Model {

  protected $table = 'tb_pengeluaran';

  // 🔹 Ambil semua pengeluaran sekolah berdasarkan tahun
  public function get_by_sekolah($sekolah_id, $tahun_id = null)
{
    // 🔹 Ambil tahun aktif dari session jika tidak dikirim
    if ($tahun_id === null) {
        $tahun_id = $this->session->userdata('tahun_id');
    }

    // 🔹 Ambil angka tahun (misal: 2026)
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

    // 🔹 Query utama mirip Rekap_model
    $this->db->select('
        p.*, 
        k.kode, 
        k.nama AS nama_kodering, 
        sa.nama AS nama_sumber_anggaran
    ');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_kodering k', 'p.kodering_id = k.id', 'left');
    $this->db->join('tb_sumber_anggaran sa', 'p.sumber_anggaran_id = sa.id', 'left');
    $this->db->join('tb_tahun_anggaran t', 'YEAR(p.tanggal) = t.tahun', 'left');
    $this->db->where('p.sekolah_id', $sekolah_id);

    // 🔹 Filter tahun sesuai tahun login
    $this->db->where('YEAR(p.tanggal)', $tahun);

    $this->db->order_by('p.tanggal', 'DESC');
    return $this->db->get()->result();
}

  // 🔹 Simpan data pengeluaran baru
  public function insert($data){
    return $this->db->insert($this->table, $data);
  }

  // 🔹 Ambil semua pengeluaran berdasarkan nomor invoice
  public function get_by_invoice($invoice_no){
    $this->db->select('
      p.*, 
      k.kode, 
      k.nama AS nama_kodering, 
      sa.nama AS nama_sumber_anggaran
    ');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_kodering k', 'p.kodering_id = k.id', 'left');
    $this->db->join('tb_sumber_anggaran sa', 'p.sumber_anggaran_id = sa.id', 'left');
    $this->db->where('p.invoice_no', $invoice_no);
    $this->db->order_by('p.tanggal','ASC');
    return $this->db->get()->result();
  }

  // 🔹 Total pengeluaran per invoice (buat rekap otomatis)
  public function sum_by_invoice($invoice_no){
    $this->db->select_sum('jumlah');
    $this->db->where('invoice_no', $invoice_no);
    $row = $this->db->get($this->table)->row();
    return $row ? $row->jumlah : 0;
  }

  // 🔹 Ambil semua pengeluaran untuk admin (semua sekolah)
  public function get_all_with_sekolah($tahun_id = null) {
    // Jika tahun_id dikirim dari controller
    if (!empty($tahun_id)) {
        $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
        $tahun = $tahun_row ? $tahun_row->tahun : date('Y');
    } else {
        $tahun = date('Y');
    }

    $this->db->select('
      p.*, 
      k.kode, 
      k.nama AS nama_kodering, 
      jb.nama AS nama_jenis_belanja, 
      sa.nama AS sumber_anggaran,
      u.nama AS nama_sekolah
    ');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_kodering k', 'p.kodering_id = k.id', 'left');
    $this->db->join('tb_kategori_belanja jb', 'p.jenis_belanja_id = jb.id', 'left');
    $this->db->join('tb_sumber_anggaran sa', 'p.sumber_anggaran_id = sa.id', 'left');
    $this->db->join('tb_user u', 'p.sekolah_id = u.id', 'left');

    // 🔹 filter tahun sesuai login
    $this->db->group_start();
    $this->db->where('p.tahun_anggaran', $tahun);
    $this->db->or_where('p.tahun', $tahun);
    $this->db->group_end();

    $this->db->order_by('p.tanggal', 'DESC');
    return $this->db->get()->result();
}

public function delete($id)
{
    $pengeluaran = $this->db->get_where('tb_pengeluaran', ['id'=>$id])->row();
    if ($pengeluaran) {
        $this->db->delete('tb_pengeluaran', ['id'=>$id]);
        $total = $this->sum_by_invoice($pengeluaran->invoice_no);

        if ($total > 0) {
            $this->load->model('Rekap_model');
            $this->Rekap_model->update_total($pengeluaran->invoice_no, $total);
        } else {
            $this->db->delete('tb_rekap_pembelanjaan', ['invoice_no'=>$pengeluaran->invoice_no]);
        }
        return true;
    }
    return false;
}

  // 🔹 Sinkronisasi otomatis ke tb_rekap_pembelanjaan
public function sync_rekap() {
    $this->load->model('Rekap_model');

    // Ambil semua invoice unik dari tabel pengeluaran
    $query = $this->db->select('DISTINCT(invoice_no) as invoice_no, sekolah_id')
                      ->from('tb_pengeluaran')
                      ->where('invoice_no IS NOT NULL', null, false)
                      ->get()
                      ->result();

    foreach ($query as $row) {
        // Cek apakah invoice sudah ada di rekap
        $exists = $this->db->get_where('tb_rekap_pembelanjaan', ['invoice_no' => $row->invoice_no])->row();

        // Hitung total transaksi per invoice
        $total = $this->sum_by_invoice($row->invoice_no);

        // Ambil detail dari salah satu baris pengeluaran (untuk salin data tambahan)
        $detail = $this->db->select('
                kegiatan, tanggal, platform, marketplace, nama_toko, alamat_toko, pembayaran,
                no_rekening, nama_bank, jenis_belanja_id, sumber_anggaran_id
            ')
            ->from('tb_pengeluaran')
            ->where('invoice_no', $row->invoice_no)
            ->limit(1)
            ->get()
            ->row();

        if (!$exists) {
            // ✅ Buat entri baru ke rekap, lengkap dengan kolom marketplace
            $this->db->insert('tb_rekap_pembelanjaan', [
                'invoice_no'         => $row->invoice_no,
                'sekolah_id'         => $row->sekolah_id,
                'tanggal'            => $detail ? $detail->tanggal : date('Y-m-d'),
                'kegiatan'           => $detail ? $detail->kegiatan : '-',
                'jenis_belanja_id'   => $detail ? $detail->jenis_belanja_id : null,
                'sumber_anggaran_id' => $detail ? $detail->sumber_anggaran_id : null,
                'nilai_transaksi'    => $total,
                'platform'           => $detail ? $detail->platform : 'Non_SIPLAH',
                'marketplace'        => $detail ? $detail->marketplace : null,
                'nama_toko'          => $detail ? $detail->nama_toko : '-',
                'alamat_toko'        => $detail ? $detail->alamat_toko : '-',
                'pembayaran'         => $detail ? $detail->pembayaran : 'Tunai',
                'no_rekening'        => $detail ? $detail->no_rekening : null,
                'nama_bank'          => $detail ? $detail->nama_bank : null
            ]);
        } else {
            // ✅ Jika sudah ada, update total + marketplace (kalau kosong)
            $update_data = ['nilai_transaksi' => $total];

            if (empty($exists->jenis_belanja_id) && !empty($detail->jenis_belanja_id)) {
                $update_data['jenis_belanja_id'] = $detail->jenis_belanja_id;
            }

            if (empty($exists->sumber_anggaran_id) && !empty($detail->sumber_anggaran_id)) {
                $update_data['sumber_anggaran_id'] = $detail->sumber_anggaran_id;
            }

            if (empty($exists->marketplace) && !empty($detail->marketplace)) {
                $update_data['marketplace'] = $detail->marketplace;
            }

            $this->db->where('invoice_no', $row->invoice_no)
                     ->update('tb_rekap_pembelanjaan', $update_data);
        }
    }
}

// Hitung total data untuk pagination
public function count_all_filtered($tahun_id, $jenjang = null, $sekolah_id = null, $status = null) {
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_user u', 'p.sekolah_id = u.id', 'left');

    $this->db->where('p.tahun_anggaran', $tahun);

    if (!empty($jenjang)) {
        $this->db->where('u.jenjang', $jenjang);
    }
    if (!empty($sekolah_id)) {
        $this->db->where('u.id', $sekolah_id);
    }
    if (!empty($status)) {
        $this->db->where('p.status', $status);
    }

    return $this->db->count_all_results();
}

// Ambil data pengeluaran dengan filter + limit pagination
public function get_all_with_filter($tahun_id, $jenjang = null, $sekolah_id = null, $limit = 15, $start = 0, $status = null) {
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = $tahun_row ? $tahun_row->tahun : date('Y');

    $this->db->select('
        p.*, 
        u.nama AS nama_sekolah, 
        u.jenjang, 
        k.kode, 
        k.nama AS nama_kodering, 
        sa.nama AS sumber_anggaran,
        jb.nama AS nama_jenis_belanja
    ');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_user u', 'p.sekolah_id = u.id', 'left');
    $this->db->join('tb_kodering k', 'p.kodering_id = k.id', 'left');
    $this->db->join('tb_sumber_anggaran sa', 'p.sumber_anggaran_id = sa.id', 'left');
    $this->db->join('tb_kategori_belanja jb', 'p.jenis_belanja_id = jb.id', 'left');

    $this->db->where('p.tahun_anggaran', $tahun);

    if (!empty($sekolah_id)) {
        $this->db->where('u.id', $sekolah_id);
    }

    if (!empty($status)) {
        $this->db->where('p.status', $status);
    }

    $this->db->order_by('p.tanggal', 'DESC');
    $this->db->limit($limit, $start);

    return $this->db->get()->result();
}

}
