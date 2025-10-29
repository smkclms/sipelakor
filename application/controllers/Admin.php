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
    $data['jumlah_sma'] = $this->db->where('jenjang_id', 5)->count_all_results('tb_user');
    $data['jumlah_slb'] = $this->db->where('jenjang_id', 6)->count_all_results('tb_user');

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
    $data['pagu_sma'] = get_total_anggaran_by_jenjang(5);
    $data['pagu_slb'] = get_total_anggaran_by_jenjang(6);

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
