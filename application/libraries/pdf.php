<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Pastikan TCPDF hanya diload sekali
if (!class_exists('TCPDF')) {
    require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');
}

class Pdf extends TCPDF
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Helper untuk membuat PDF dari HTML dengan mudah
     */
    public function createPDF($html, $filename = 'laporan', $stream = true)
    {
        $this->AddPage();
        $this->SetFont('helvetica', '', 10);
        $this->writeHTML($html, true, false, true, false, '');
        if ($stream) {
            $this->Output($filename . '.pdf', 'I');
        } else {
            $this->Output(FCPATH . 'downloads/' . $filename . '.pdf', 'F');
        }
    }
}
