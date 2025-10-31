<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kodering extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Kodering_model', 'Kategori_belanja_model']);
    if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
        redirect('auth/login');
    }
  }

  public function index() {
    $data['kategori_belanja'] = $this->Kategori_belanja_model->get_all();
    $data['kodering'] = $this->Kodering_model->get_all();

    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/kodering_list', $data);
    $this->load->view('template/footer');
  }

  public function tambah() {
    $data = [
      'kode' => $this->input->post('kode'),
      'nama' => $this->input->post('nama'),
      'kategori_id' => $this->input->post('kategori_id')
    ];
    $this->Kodering_model->insert($data);
    redirect('kodering');
  }
  public function import_excel()
{
    $this->load->library('PHPExcel_lib');
    $this->load->model('Kodering_model');
    $this->load->model('Kategori_belanja_model');

    if (!empty($_FILES['file_excel']['name'])) {
        $file = $_FILES['file_excel']['tmp_name'];

        // Load Excel
        $excel = PHPExcel_IOFactory::load($file);
        $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);

        $insert_data = [];
        $numrow = 1;

        foreach ($sheet as $row) {
            if ($numrow > 1) { // skip header baris pertama
                $kode = trim($row['A']);
                $nama = trim($row['B']);
                $kategori_input = isset($row['C']) ? trim($row['C']) : '';

                if (empty($kode) || empty($nama)) {
                    $numrow++;
                    continue; // skip baris kosong
                }

                // 🔹 Pastikan kategori angka valid (1,2,3,...)
                $kategori_id = (int)$kategori_input;
                if (!in_array($kategori_id, [1, 2, 3])) {
                    $kategori_id = 1; // fallback default ke Barang Habis Pakai
                }

                // 🔹 Cek apakah kode sudah ada
                $exists = $this->db->get_where('tb_kodering', ['kode' => $kode])->row();
                if ($exists) {
                    $numrow++;
                    continue; // skip duplikat
                }

                $insert_data[] = [
                    'kode' => $kode,
                    'nama' => $nama,
                    'kategori_id' => $kategori_id
                ];
            }
            $numrow++;
        }

        if (!empty($insert_data)) {
            $this->db->insert_batch('tb_kodering', $insert_data);
            $this->session->set_flashdata('success', count($insert_data) . ' data kodering berhasil diimport!');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada data valid untuk diimport!');
        }
    } else {
        $this->session->set_flashdata('error', 'File Excel belum dipilih!');
    }

    redirect('kodering');
}

public function download_template()
{
    $this->load->library('PHPExcel_lib');
    $excel = new PHPExcel();
    $sheet = $excel->setActiveSheetIndex(0);
    $sheet->setTitle('Template Kodering');

    // Header
    $sheet->setCellValue('A1', 'Kode');
    $sheet->setCellValue('B1', 'Nama Kodering');
    $sheet->setCellValue('C1', 'Kategori (1=Barang, 2=Modal Alat dan Mesin, 3=Jasa, 4=Modal Aset)');

    $style_header = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
        'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]],
        'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D9E1F2']]
    ];
    $sheet->getStyle('A1:C1')->applyFromArray($style_header);
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);

    // Contoh isi
    $sheet->setCellValue('A2', '5.1.02.01.01.0055');
    $sheet->setCellValue('B2', 'Belanja Makanan dan Minuman');
    $sheet->setCellValue('C2', '1');

    $sheet->setCellValue('A3', '5.2.05.01.01.0001');
    $sheet->setCellValue('B3', 'Belanja Modal Buku Umum');
    $sheet->setCellValue('C3', '2');

    $sheet->setCellValue('A4', '5.1.02.02.03.0012');
    $sheet->setCellValue('B4', 'Belanja Jasa Kebersihan');
    $sheet->setCellValue('C4', '3');

    // Output
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Template_Kodering.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save('php://output');
}

}
