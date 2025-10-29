<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekolah extends CI_Controller {

    public function __construct(){
        parent::__construct();
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // Pastikan role sekolah
        if ($this->session->userdata('role') != 'sekolah') {
            show_error('Akses ditolak. Halaman ini hanya untuk user sekolah.', 403);
        }
    }

   public function index() {
    if ($this->session->userdata('role') != 'sekolah') {
        show_error('Akses ditolak.', 403);
    }

    $data['nama'] = $this->session->userdata('nama');

    // 🔹 Ambil tahun anggaran dari session login
    $tahun_id = $this->session->userdata('tahun_id');
    $tahun_aktif = date('Y'); // fallback default

    if ($tahun_id) {
        $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
        if ($tahun_row) {
            $tahun_aktif = $tahun_row->tahun;
        }
    }

    // 🔹 Ambil sekolah_id dari session
    $sekolah_id = $this->session->userdata('sekolah_id');

    // ==============================
    // 🔹 CARD 1: Total Pagu Anggaran
    // ==============================
    $this->db->select_sum('jumlah', 'total');
    $this->db->from('tb_anggaran_sekolah');
    $this->db->where('sekolah_id', $sekolah_id);
    if ($tahun_id) {
        $this->db->where('tahun_id', $tahun_id);
    }
    $row = $this->db->get()->row();
    $total_pagu = isset($row->total) ? $row->total : 0;

    // ==============================
    // 🔹 CARD 2: Total Pengeluaran
    // ==============================
    $this->db->select_sum('jumlah', 'total');
    $this->db->from('tb_pengeluaran');
    $this->db->where('sekolah_id', $sekolah_id);
    if ($tahun_id) {
        $this->db->where('tahun_anggaran', $tahun_aktif);
    }
    $row = $this->db->get()->row();
    $total_pengeluaran = isset($row->total) ? $row->total : 0;

    // ==============================
    // 🔹 CARD 3: Sisa Anggaran
    // ==============================
    $sisa_anggaran = $total_pagu - $total_pengeluaran;

    // ===============================================
    // 🔹 DETAIL 1: Pengeluaran berdasarkan sumber
    // ===============================================
    $this->db->select('s.nama as sumber, SUM(p.jumlah) as total');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_sumber_anggaran s', 's.id = p.sumber_anggaran_id', 'left');
    $this->db->where('p.sekolah_id', $sekolah_id);
    if ($tahun_id) {
        $this->db->where('p.tahun_anggaran', $tahun_aktif);
    }
    $this->db->group_by('s.nama');
    $data['by_sumber'] = $this->db->get()->result();

    // ===============================================
    // 🔹 DETAIL 2: Pengeluaran berdasarkan kategori
    // ===============================================
    $this->db->select('k.nama as kategori, SUM(p.jumlah) as total');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_kategori_belanja k', 'k.id = p.jenis_belanja_id', 'left');
    $this->db->where('p.sekolah_id', $sekolah_id);
    if ($tahun_id) {
        $this->db->where('p.tahun_anggaran', $tahun_aktif);
    }
    $this->db->group_by('k.nama');
    $data['by_kategori'] = $this->db->get()->result();

    // ===============================================
    // 🔹 DETAIL 3: Pengeluaran berdasarkan kodering
    // ===============================================
    $this->db->select('ko.nama as kodering, SUM(p.jumlah) as total');
    $this->db->from('tb_pengeluaran p');
    $this->db->join('tb_kodering ko', 'ko.id = p.kodering_id', 'left');
    $this->db->where('p.sekolah_id', $sekolah_id);
    if ($tahun_id) {
        $this->db->where('p.tahun_anggaran', $tahun_aktif);
    }
    $this->db->group_by('ko.nama');
    $data['by_kodering'] = $this->db->get()->result();

    // ===============================================
    // 🔹 GRAFIK: Pengeluaran per bulan
    // ===============================================
    $this->db->select('MONTH(p.tanggal) as bulan, SUM(p.jumlah) as total');
    $this->db->from('tb_pengeluaran p');
    $this->db->where('p.sekolah_id', $sekolah_id);
    if ($tahun_id) {
        $this->db->where('p.tahun_anggaran', $tahun_aktif);
    }
    $this->db->group_by('MONTH(p.tanggal)');
    $this->db->order_by('bulan', 'ASC');
    $data['chart_data'] = $this->db->get()->result();

    // simpan hasil ke data view
    $data['total_pagu'] = $total_pagu;
    $data['total_pengeluaran'] = $total_pengeluaran;
    $data['sisa_anggaran'] = $sisa_anggaran;
    $data['tahun_aktif'] = $tahun_aktif;

    // load view
    $this->load->view('template/header');
    $this->load->view('template/sidebar_sekolah');
    $this->load->view('sekolah/dashboard', $data);
    $this->load->view('template/footer');
}

}
