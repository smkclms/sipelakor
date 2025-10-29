<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Rekap_model', 'Pengeluaran_model', 'Tahun_model']);
        $this->load->library('session');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // 🔹 Halaman daftar rekap untuk sekolah (user)
   public function index()
{
    if ($this->session->userdata('role') != 'sekolah') {
        redirect('rekap/admin');
    }

    $this->load->model('Rekap_model');
    $this->load->model('Tahun_model');

    $tahun_id = $this->input->get('tahun_id') ?: $this->session->userdata('tahun_id');
    $data['tahun_all'] = $this->Tahun_model->get_all();
    $data['rekap'] = $this->Rekap_model->get_by_sekolah($this->session->userdata('user_id'), $tahun_id);

    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $data['tahun_aktif'] = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

    $this->db->select_sum('nilai_transaksi', 'total_penggunaan');
    $this->db->where('sekolah_id', $this->session->userdata('user_id'));
    $this->db->where('YEAR(tanggal)', $data['tahun_aktif']);
    $total_row = $this->db->get('tb_rekap_pembelanjaan')->row();
    $data['total_penggunaan'] = $total_row ? $total_row->total_penggunaan : 0;

    // 🔹 view sekolah
    $this->load->view('template/header');
    $this->load->view('template/sidebar_sekolah');
    $this->load->view('sekolah/rekap_list', $data);
    $this->load->view('template/footer');
}
// public function detail($invoice_no)
// {
    
public function detail()
{
    $invoice_no = $this->input->get('invoice_no');
// Pastikan model sudah diload
    $this->load->model('Rekap_model');
    $this->load->model('Pengeluaran_model');

    // Ambil data rekap dan pengeluaran berdasarkan invoice
    $data['rekap'] = $this->Rekap_model->get($invoice_no);
    $data['pengeluaran'] = $this->Pengeluaran_model->get_by_invoice($invoice_no);

    // Jika invoice tidak ditemukan → tampilkan 404
    if (!$data['rekap']) {
        show_404();
        return;
    }

    // Pilih layout berdasarkan role
    $role = strtolower($this->session->userdata('role'));

    // Header selalu sama
    $this->load->view('template/header');

    if ($role == 'admin') {
        $this->load->view('template/sidebar_admin');
    } else {
        $this->load->view('template/sidebar_sekolah');
    }

    // Load halaman detail
    $this->load->view('rekap/detail', $data);
    $this->load->view('template/footer');
}

    // 🔹 Halaman daftar rekap untuk admin
    public function admin() {
        // Hanya admin yang boleh
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak.', 403);
        }

        $tahun_id = $this->input->get('tahun_id') ?: $this->session->userdata('tahun_id');
        $data['tahun_all'] = $this->Tahun_model->get_all();
        $data['rekap'] = $this->Rekap_model->get_all_by_admin_tahun($tahun_id);

        $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
        $data['tahun_aktif'] = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

        $this->db->select_sum('nilai_transaksi', 'total_penggunaan');
        $this->db->where('YEAR(tanggal)', $data['tahun_aktif']);
        $total_row = $this->db->get('tb_rekap_pembelanjaan')->row();
        $data['total_penggunaan'] = $total_row ? $total_row->total_penggunaan : 0;

        // View admin
        $this->load->view('template/header');
        $this->load->view('template/sidebar_admin');
        $this->load->view('admin/rekap_list', $data);
        $this->load->view('template/footer');
    }

    // 🔹 Tambah data rekap (hanya sekolah)
    public function tambah() {
        if ($this->session->userdata('role') != 'sekolah') show_error('Akses ditolak.', 403);

        $this->load->model('Kategori_belanja_model');

        if ($this->input->post()) {
            $data = [
                'invoice_no' => $this->Rekap_model->generate_invoice(),
                'sekolah_id' => $this->session->userdata('user_id'),
                'tanggal' => $this->input->post('tanggal'),
                'kegiatan' => $this->input->post('kegiatan'),
                'jenis_belanja_id' => $this->input->post('jenis_belanja_id'),
                'platform' => $this->input->post('platform'),
                'nama_toko' => $this->input->post('nama_toko'),
                'alamat_toko' => $this->input->post('alamat_toko'),
                'pembayaran' => $this->input->post('pembayaran'),
                'no_rekening' => $this->input->post('no_rekening'),
                'nama_bank' => $this->input->post('nama_bank'),
            ];
            $this->Rekap_model->insert($data);
            redirect('pengeluaran');
        }

        $data['kategori'] = $this->Kategori_belanja_model->get_all();

        $this->load->view('template/header');
        $this->load->view('template/sidebar_sekolah');
        $this->load->view('sekolah/rekap_tambah', $data);
        $this->load->view('template/footer');
    }
}

 

