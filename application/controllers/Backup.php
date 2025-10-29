<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->load->helper('download');
    }

    public function index()
    {
        // Konfigurasi backup
        $prefs = array(
            'format'      => 'zip',
            'filename'    => 'backup_database.sql'
        );

        // Proses backup database
        $backup =& $this->dbutil->backup($prefs);

        // Nama file hasil backup
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.zip';

        // Langsung download ke browser (tidak disimpan di server)
        force_download($filename, $backup);
    }
}
