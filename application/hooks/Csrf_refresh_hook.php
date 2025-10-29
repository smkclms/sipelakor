<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csrf_refresh_hook {
    public function refresh() {
        $CI =& get_instance();
        if ($CI->input->is_ajax_request()) {
            // perbarui token otomatis
            $CI->security->csrf_set_cookie();
            header('X-CSRF-Token: '.$CI->security->get_csrf_hash());
        }
    }
}
