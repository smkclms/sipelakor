<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'sekolah') {
            redirect('auth');
        }
        $this->load->model('User_model');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Profil Saya';
        $data['user'] = $this->User_model->get_by_id($user_id);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_sekolah', $data);
        $this->load->view('sekolah/profil', $data);
        $this->load->view('template/footer');
    }

    public function update() {
        $user_id = $this->session->userdata('user_id');
        $data = [
            'nama' => $this->input->post('nama', true),
            'alamat' => $this->input->post('alamat', true),
            'email' => $this->input->post('email', true),
            'kepala_sekolah' => $this->input->post('kepala_sekolah', true),
            'bendahara' => $this->input->post('bendahara', true),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->User_model->update($user_id, $data);
        $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
        redirect('profil');
    }

    public function ganti_password()
{
    $user_id = $this->session->userdata('user_id');
    $password_baru = password_hash($this->input->post('password_baru', true), PASSWORD_BCRYPT);

    $this->db->where('id', $user_id);
    $this->db->update('tb_user', [
        'password' => $password_baru,
        'password_changed_at' => date('Y-m-d H:i:s'), // 🕒 simpan waktu ganti password
        'updated_at' => date('Y-m-d H:i:s')           // tetap update juga kolom utama
    ]);

    $this->session->set_flashdata('success', 'Password berhasil diperbarui!');
    redirect('profil');
}


}
