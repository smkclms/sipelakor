<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_management extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('role') != 'admin') {
            redirect('auth');
        }
        $this->load->model('User_model');
    }

    public function index() {
    $data['title'] = 'Manajemen Pengguna';
    $data['users'] = $this->User_model->get_all_sekolah();
    $data['cabang'] = $this->db->get('tb_cabang')->result();
    $data['jenjang'] = $this->db->get('tb_jenjang')->result();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_admin', $data);
    $this->load->view('admin/user_management', $data);
    $this->load->view('template/footer');
}

public function add()
{
    $data = array(
        'nama' => $this->input->post('nama', true),
        'alamat' => $this->input->post('alamat', true),
        'email' => $this->input->post('email', true),
        'no_kontrol' => strtoupper($this->input->post('no_kontrol', true)),
        'password' => password_hash($this->input->post('password', true), PASSWORD_BCRYPT),
        'role' => $this->input->post('role', true),
        'cabang_id' => $this->input->post('cabang_id', true),
        'jenjang_id' => $this->input->post('jenjang_id', true),
        'kepala_sekolah' => $this->input->post('kepala_sekolah', true),
        'bendahara' => $this->input->post('bendahara', true),
        'aktif' => 1,
        'created_at' => date('Y-m-d H:i:s')
    );

    $this->User_model->create($data);
    $this->session->set_flashdata('success', 'Data pengguna berhasil ditambahkan!');
    redirect('user_management');
}


public function update($id) {
    $data = array(
        'nama' => $this->input->post('nama', true),
        'alamat' => $this->input->post('alamat', true),
        'email' => $this->input->post('email', true),
        'no_kontrol' => strtoupper($this->input->post('no_kontrol', true)),
        'role' => $this->input->post('role', true),
        'cabang_id' => $this->input->post('cabang_id', true),
        'jenjang_id' => $this->input->post('jenjang_id', true),
        'kepala_sekolah' => $this->input->post('kepala_sekolah', true),
        'bendahara' => $this->input->post('bendahara', true),
        'updated_at' => date('Y-m-d H:i:s')
    );

    // Password opsional
    if ($this->input->post('password')) {
        $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
    }

    $this->User_model->update($id, $data);
    $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui!');
    redirect('user_management');
}


    public function delete($id) {
        $this->db->delete('tb_user', ['id' => $id]);
        $this->session->set_flashdata('success', 'Sekolah berhasil dihapus!');
        redirect('user_management');
    }
}
