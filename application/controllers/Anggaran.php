<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Anggaran_model', 'Sumber_anggaran_model', 'Tahun_model']);
        $this->load->library('form_validation');

        if (!$this->session->userdata('sekolah_id')) {
            redirect('auth');
        }
    }

    // 🔹 Halaman utama daftar anggaran sekolah
    public function index() {
        $data['title'] = 'Anggaran Sekolah';

        $sekolah_id = $this->session->userdata('sekolah_id');
        $tahun_id   = $this->session->userdata('tahun_id');

        // Ambil data dari model
        $data['anggaran']         = $this->Anggaran_model->get_by_sekolah($sekolah_id, $tahun_id);
        $data['sumber_anggaran']  = $this->Sumber_anggaran_model->get_all(); // ✅ pastikan tidak NULL
        $data['tahun_aktif']      = $this->Tahun_model->get_aktif();

        // Cegah error jika model return NULL
        if (empty($data['sumber_anggaran'])) {
            $data['sumber_anggaran'] = [];
        }

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_sekolah', $data);
        $this->load->view('sekolah/anggaran_list', $data);
        $this->load->view('template/footer');
    }

    // 🔹 Tambah anggaran
    public function tambah() {
        $this->form_validation->set_rules('sumber_id', 'Sumber Anggaran', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = [
                'sekolah_id' => $this->session->userdata('sekolah_id'),
                'sumber_id'  => $this->input->post('sumber_id', true),
                'tahun_id'   => $this->session->userdata('tahun_id'),
                'jumlah'     => $this->input->post('jumlah', true),
                'tersisa'    => $this->input->post('jumlah', true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->Anggaran_model->insert($data);
            $this->session->set_flashdata('success', 'Anggaran berhasil ditambahkan!');
            redirect('anggaran');
        }
    }

    // 🔹 Edit anggaran
    public function edit($id) {
        $data = [
            'sumber_id' => $this->input->post('sumber_id', true),
            'jumlah'    => $this->input->post('jumlah', true),
            'tersisa'   => $this->input->post('tersisa', true),
            'updated_at'=> date('Y-m-d H:i:s')
        ];

        $this->Anggaran_model->update($id, $data);
        $this->session->set_flashdata('success', 'Anggaran berhasil diperbarui!');
        redirect('anggaran');
    }

    // 🔹 Hapus anggaran
    public function hapus($id) {
        $this->Anggaran_model->delete($id);
        $this->session->set_flashdata('success', 'Anggaran berhasil dihapus!');
        redirect('anggaran');
    }
}
