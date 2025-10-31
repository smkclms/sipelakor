<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_belanja extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('Kategori_belanja_model');
    $this->load->library('form_validation');

    // Cek login admin
    if ($this->session->userdata('role') != 'admin') {
        redirect('auth');
    }
  }

  // 🔹 Halaman utama (daftar + form tambah)
  public function index() {
    $data['title'] = 'Manajemen Kategori Belanja';
    $data['kategori'] = $this->Kategori_belanja_model->get_all();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/kategori_belanja_list', $data);
    $this->load->view('template/footer');
  }

  // 🔹 Tambah kategori
  public function tambah() {
    $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', 'Nama kategori wajib diisi!');
    } else {
        $data = [
            'nama' => $this->input->post('nama_kategori', TRUE)
        ];
        $this->Kategori_belanja_model->insert($data);
        $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan!');
    }
    redirect('kategori_belanja');
  }

  // 🔹 Edit kategori
  public function edit($id){
    $kategori = $this->Kategori_belanja_model->get($id);
    if (!$kategori) show_404();

    $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim');

    if ($this->form_validation->run() == FALSE) {
        $data['title'] = 'Edit Kategori Belanja';
        $data['kategori'] = $kategori;

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin');
        $this->load->view('admin/kategori_belanja_edit', $data);
        $this->load->view('template/footer');

    } else {
        $data = ['nama' => $this->input->post('nama_kategori', TRUE)];
        $this->Kategori_belanja_model->update($id, $data);
        $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
        redirect('kategori_belanja');
    }
  }

  // 🔹 Hapus kategori
  public function hapus($id){
    $this->Kategori_belanja_model->delete($id);
    $this->session->set_flashdata('success', 'Kategori berhasil dihapus!');
    redirect('kategori_belanja');
  }
}
