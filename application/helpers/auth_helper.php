<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function cek_login(){
  $CI =& get_instance();
  if (!$CI->session->userdata('logged_in')){
    redirect('auth/login');
  }
}

function cek_role($role){
  $CI =& get_instance();
  if ($CI->session->userdata('role') != $role){
    show_error('Anda tidak memiliki akses', 403);
  }
}
function tahun_aktif_id(){
  $CI =& get_instance();
  $CI->load->model('Tahun_model');
  return $CI->Tahun_model->get_active()->id;
}
