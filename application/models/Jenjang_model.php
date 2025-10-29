<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenjang_model extends CI_Model {
  public function get_all() {
    return $this->db->order_by('id', 'ASC')->get('tb_jenjang')->result();
  }
}
