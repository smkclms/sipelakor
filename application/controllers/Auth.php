<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model(['User_model', 'Tahun_model']);
    $this->load->library('session');
  }

  public function login(){
    if ($this->input->post()) {
      $no_kontrol = $this->input->post('no_kontrol', true);
      $password   = $this->input->post('password', true);
      $tahun_id   = $this->input->post('tahun_id', true); // dari dropdown login

      $user = $this->User_model->get_by_kontrol($no_kontrol);

      if ($user && password_verify($password, $user->password)) {
        // Ambil tahun aktif dari tabel jika tidak dipilih
        $tahun_aktif = $this->Tahun_model->get_aktif();
        $tahun_id_final = !empty($tahun_id) ? $tahun_id : ($tahun_aktif ? $tahun_aktif->id : 0);

        // Data session utama
        $sess = [
          'user_id'   => $user->id,
          'nama'      => $user->nama,
          'role'      => $user->role,
          'logged_in' => true,
          'tahun_id'  => $tahun_id_final
        ];

        // Jika role sekolah → tambahkan sekolah_id
        if ($user->role == 'sekolah') {
          $sess['sekolah_id'] = $user->id;
        }

        // Simpan session
        $this->session->set_userdata($sess);

        // Redirect berdasarkan role
        if ($user->role == 'admin') {
          redirect('admin');
        } else {
          redirect('sekolah');
        }

      } else {
        $this->session->set_flashdata('error', 'No kontrol atau password salah');
        redirect('auth/login');
      }
    }

    // Jika belum login → tampilkan form login
    $data['tahun'] = $this->Tahun_model->get_all();
    
    $this->load->view('auth/login', $data);
    $this->load->view('template/footer');
  }

  public function logout(){
    $this->session->sess_destroy();
    redirect('auth/login');
  }
//   public function ganti_tahun()
// {
//     $tahun_id = $this->input->post('tahun_id', true);

//     if ($tahun_id) {
//         $this->session->set_userdata('tahun_id', $tahun_id);
//         $tahun_row = $this->db->get_where('tb_tahun_anggaran', array('id' => $tahun_id))->row();

//         $tahun_text = '-';
//         if ($tahun_row && isset($tahun_row->tahun)) {
//             $tahun_text = $tahun_row->tahun;
//         }

//         $this->session->set_flashdata('success', 'Tahun anggaran berhasil diubah ke ' . $tahun_text);
//     }

//     // ✅ versi aman untuk redirect
//     if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
//         redirect($_SERVER['HTTP_REFERER']);
//     } else {
//         redirect(base_url());
//     }
// }

}
