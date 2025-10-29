<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

class PHPExcel_lib {

    // === Export Laporan Tagihan ===
    public function export_tagihan($data, $filename = 'Laporan_Tagihan') {
        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Kontrol');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Bulan');
        $sheet->setCellValue('E1', 'Tahun');
        $sheet->setCellValue('F1', 'Pemakaian (m³)');
        $sheet->setCellValue('G1', 'Biaya');
        $sheet->setCellValue('H1', 'Status');

        $sheet->getDefaultStyle()->getNumberFormat()->setFormatCode('@');

        $row = 2; $no = 1;
        foreach ($data as $r) {
            $sheet->setCellValueExplicit('A'.$row, $no++, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B'.$row, isset($r['no_kontrol']) ? $r['no_kontrol'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C'.$row, isset($r['name']) ? $r['name'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D'.$row, isset($r['bulan']) ? $r['bulan'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E'.$row, isset($r['tahun']) ? $r['tahun'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F'.$row, isset($r['pemakaian']) ? $r['pemakaian'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G'.$row, isset($r['biaya']) ? $r['biaya'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H'.$row, isset($r['lunas']) ? ucfirst($r['lunas']) : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
        }

        $this->_download($excel, $filename);
    }

    // === Export Rekap Penggunaan ===
    public function export_rekap($data, $filename = 'Rekap_Penggunaan') {
        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Kontrol');
        $sheet->setCellValue('C1', 'Nama Pelanggan');
        $sheet->setCellValue('D1', 'Total Pemakaian (m³)');
        $sheet->setCellValue('E1', 'Total Biaya (Rp)');

        $sheet->getDefaultStyle()->getNumberFormat()->setFormatCode('@');

        $row = 2; $no = 1;
        foreach ($data as $r) {
            $sheet->setCellValueExplicit('A'.$row, $no++, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B'.$row, isset($r['no_kontrol']) ? $r['no_kontrol'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C'.$row, isset($r['name']) ? $r['name'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D'.$row, isset($r['total_pemakaian']) ? $r['total_pemakaian'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E'.$row, isset($r['total_biaya']) ? $r['total_biaya'] : '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
        }

        $this->_download($excel, $filename);
    }

    // === Helper untuk output Excel ===
    private function _download($excel, $filename) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
    }
}
