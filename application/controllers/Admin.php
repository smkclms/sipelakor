<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct(){
        parent::__construct();
        // Pastikan user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // Pastikan role-nya admin
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak. Halaman ini hanya untuk admin.', 403);
        }
    }

    public function index(){
    $data['nama'] = $this->session->userdata('nama');
    // 🔹 Ambil tahun dari session login
    $tahun_id = $this->session->userdata('tahun_id');
    $tahun_aktif = date('Y'); // fallback default

    if ($tahun_id) {
        $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
        if ($tahun_row) {
            $tahun_aktif = $tahun_row->tahun;
        }
    }


    // Hitung jumlah sekolah per jenjang
    $data['jumlah_smk'] = $this->db->where('jenjang_id', 4)->count_all_results('tb_user');
    $data['jumlah_sma'] = $this->db->where('jenjang_id', 3)->count_all_results('tb_user');
    $data['jumlah_slb'] = $this->db->where('jenjang_id', 5)->count_all_results('tb_user');

    // Fungsi bantu untuk ambil total pagu berdasarkan jenjang_id
    function get_total_anggaran_by_jenjang($jenjang_id) {
    $CI =& get_instance();
    $CI->db->select_sum('a.jumlah', 'total');
    $CI->db->from('tb_anggaran_sekolah a');
    $CI->db->join('tb_user u', 'u.id = a.sekolah_id', 'left');
    $CI->db->join('tb_tahun_anggaran t', 'a.tahun_id = t.id', 'left');

    // 🔹 Ambil tahun dari session login
    $tahun_id = $CI->session->userdata('tahun_id');
    if ($tahun_id) {
        $CI->db->where('a.tahun_id', $tahun_id);
    }

    $CI->db->where('u.jenjang_id', $jenjang_id);
    $CI->db->where('u.role', 'sekolah');
    $CI->db->where('u.aktif', 1);

    $row = $CI->db->get()->row();
    return isset($row->total) ? $row->total : 0;
}


    // Ambil total pagu per jenjang
    $data['pagu_smk'] = get_total_anggaran_by_jenjang(4);
    $data['pagu_sma'] = get_total_anggaran_by_jenjang(3);
    $data['pagu_slb'] = get_total_anggaran_by_jenjang(5);
    // ===============================================================
// 🔹 DATA PER SUMBER ANGGARAN UNTUK SETIAP JENJANG
// ===============================================================
$jenjang_list = array(
    array('id' => 4, 'nama' => 'SMK'),
    array('id' => 3, 'nama' => 'SMA'),
    array('id' => 5, 'nama' => 'SLB')
);

$jenjang_summary = array();

foreach ($jenjang_list as $j) {
    $this->db->select('sa.id, sa.nama');
    $this->db->from('tb_anggaran_sekolah a');
    $this->db->join('tb_user u', 'u.id = a.sekolah_id', 'left');
    $this->db->join('tb_sumber_anggaran sa', 'sa.id = a.sumber_id', 'left');
    $this->db->where('u.jenjang_id', $j['id']);
    if ($tahun_id) {
        $this->db->where('a.tahun_id', $tahun_id);
    }
    $this->db->group_by('sa.id');
    $sumber_list = $this->db->get()->result();

    $sumber_summary = array();

    foreach ($sumber_list as $s) {
        // Total sekolah dengan sumber anggaran ini
        $this->db->select('COUNT(DISTINCT a.sekolah_id) as jumlah');
        $this->db->from('tb_anggaran_sekolah a');
        $this->db->join('tb_user u', 'u.id = a.sekolah_id', 'left');
        $this->db->where('u.jenjang_id', $j['id']);
        $this->db->where('a.sumber_id', $s->id);
        if ($tahun_id) $this->db->where('a.tahun_id', $tahun_id);
        $row_jumlah = $this->db->get()->row();
        $jumlah_sekolah = isset($row_jumlah->jumlah) ? $row_jumlah->jumlah : 0;

        // Total pagu
        $this->db->select_sum('a.jumlah', 'total');
        $this->db->from('tb_anggaran_sekolah a');
        $this->db->join('tb_user u', 'u.id = a.sekolah_id', 'left');
        $this->db->where('u.jenjang_id', $j['id']);
        $this->db->where('a.sumber_id', $s->id);
        if ($tahun_id) $this->db->where('a.tahun_id', $tahun_id);
        $row_pagu = $this->db->get()->row();
        $pagu = isset($row_pagu->total) ? $row_pagu->total : 0;

        // Total pengeluaran
        $this->db->select_sum('p.jumlah', 'total');
        $this->db->from('tb_pengeluaran p');
        $this->db->join('tb_user u', 'u.id = p.sekolah_id', 'left');
        $this->db->where('u.jenjang_id', $j['id']);
        $this->db->where('p.sumber_anggaran_id', $s->id);
        if ($tahun_id) $this->db->where('p.tahun_anggaran', $tahun_aktif);
        $row_peng = $this->db->get()->row();
        $pengeluaran = isset($row_peng->total) ? $row_peng->total : 0;

        $sisa = $pagu - $pengeluaran;
        $persen = ($pagu > 0) ? round(($pengeluaran / $pagu) * 100) : 0;

        $sumber_summary[] = array(
            'nama' => $s->nama,
            'jumlah_sekolah' => $jumlah_sekolah,
            'pagu' => $pagu,
            'pengeluaran' => $pengeluaran,
            'sisa' => $sisa,
            'persen' => $persen
        );
    }

    $jenjang_summary[] = array(
        'jenjang' => $j['nama'],
        'detail' => $sumber_summary
    );
}

$data['jenjang_summary'] = $jenjang_summary;

// ===============================================================
// 🔹 RINCIAN PENGELUARAN GLOBAL BERDASARKAN SUMBER (PAKAI DATA JENJANG YANG SUDAH DIHITUNG)
// ===============================================================

// Gabungkan pagu dan pengeluaran dari semua jenjang berdasarkan sumber
$sumber_global = [];

if (!empty($jenjang_summary)) {
    foreach ($jenjang_summary as $jenjang) {
        if (!empty($jenjang['detail'])) {
            foreach ($jenjang['detail'] as $sumber) {
                $nama_sumber = $sumber['nama'];

                if (!isset($sumber_global[$nama_sumber])) {
                    $sumber_global[$nama_sumber] = [
                        'sumber'       => $nama_sumber,
                        'pagu'         => 0,
                        'pengeluaran'  => 0
                    ];
                }

                $sumber_global[$nama_sumber]['pagu']        += $sumber['pagu'];
                $sumber_global[$nama_sumber]['pengeluaran'] += $sumber['pengeluaran'];
            }
        }
    }
}

$data['by_sumber'] = array_values($sumber_global);



// =======================================================
// DETAIL RINCIAN PENGELUARAN UNTUK ADMIN
// =======================================================

// 🔹 Berdasarkan sumber anggaran (dengan total pagu dan total pengeluaran)
$this->db->select("
    sa.nama as sumber,
    SUM(p.jumlah) as total,
    (
        SELECT SUM(a.jumlah)
        FROM tb_anggaran_sekolah a
        WHERE a.sumber_id = sa.id
        " . ($tahun_id ? "AND a.tahun_id = {$tahun_id}" : "") . "
    ) as pagu
");
$this->db->from('tb_pengeluaran p');
$this->db->join('tb_sumber_anggaran sa', 'sa.id = p.sumber_anggaran_id', 'left');
if ($tahun_id) {
    $this->db->where('p.tahun_anggaran', $tahun_aktif);
}
$this->db->group_by('sa.id');
$this->db->order_by('sa.nama', 'DESC');
$data['by_sumber'] = $this->db->get()->result();



// Berdasarkan kategori belanja
$this->db->select('k.nama as kategori, SUM(p.jumlah) as total');
$this->db->from('tb_pengeluaran p');
$this->db->join('tb_kategori_belanja k', 'k.id = p.jenis_belanja_id', 'left');
if ($tahun_id) {
    $this->db->where('p.tahun_anggaran', $tahun_aktif);
}
$this->db->group_by('k.nama');
$this->db->order_by('k.nama', 'ASC');
$data['by_kategori'] = $this->db->get()->result();

// Berdasarkan kodering
$this->db->select('ko.nama as kodering, SUM(p.jumlah) as total');
$this->db->from('tb_pengeluaran p');
$this->db->join('tb_kodering ko', 'ko.id = p.kodering_id', 'left');
if ($tahun_id) {
    $this->db->where('p.tahun_anggaran', $tahun_aktif);
}
$this->db->group_by('ko.nama');
$this->db->order_by('ko.nama', 'ASC');
$data['by_kodering'] = $this->db->get()->result();


    // Load tampilan dashboard
    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/dashboard', $data);
    $this->load->view('template/footer');
}



    public function sekolah()
{
    $this->load->model(array('User_model', 'Anggaran_model', 'Sumber_anggaran_model', 'Tahun_model'));

    // ambil filter dari input GET
    $jenjang = $this->input->get('jenjang');
    $tahun_id = $this->input->get('tahun_id');

    // kalau tidak dipilih, pakai tahun aktif
    if (empty($tahun_id)) {
        $tahun_aktif = $this->Tahun_model->get_aktif();
        $tahun_id = isset($tahun_aktif->id) ? $tahun_aktif->id : 0;
    }

    // ambil semua sekolah sesuai jenjang
    $sekolah = $this->User_model->get_all_sekolah($jenjang);

    // ambil semua tahun (buat dropdown filter)
    $data['tahun_list'] = $this->Tahun_model->get_all();
    $data['tahun_id']   = $tahun_id;

    // tambahkan data anggaran per sekolah sesuai tahun
    foreach ($sekolah as &$s) {
        $s->anggaran = $this->Anggaran_model->get_by_sekolah($s->id, $tahun_id);
    }

    $data['title']    = 'Data Sekolah & Anggaran';
    $data['sekolah']  = $sekolah;

    $this->load->view('template/header', $data);
    $this->load->view('admin/data_sekolah', $data);
    $this->load->view('template/footer');
}


}
