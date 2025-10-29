<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate_template extends CI_Controller {

    public function index()
    {
        $this->load->library('PHPExcel_lib');
        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);
        $sheet->setTitle('Template Pengeluaran');

        // Header kolom lengkap
        $headers = [
            "Sumber Anggaran",
            "Kegiatan",
            "No Invoice / Virtual Account",
            "Tanggal (YYYY-MM-DD)",
            "Kodering",
            "Jenis Belanja",
            "Uraian",
            "Jumlah",
            "Platform (SIPLAH / Non_SIPLAH)",
            "Nama Toko / Penyedia",
            "Alamat Toko",
            "Pembayaran (Tunai / Non-Tunai)",
            "No Rekening",
            "Nama Bank"
        ];

        // Gaya
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9E1F2']
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->applyFromArray($styleHeader);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Contoh data
        $data = [
            ['BOS Reguler', 'Kegiatan Sumatif', 'INV-2025-001', '2025-02-01', '521213', 'Barang & Jasa', 'ATK Ujian', 150000, 'Non_SIPLAH', 'Toko Maju Jaya', 'Jl. Merdeka No.12', 'Tunai', '1234567890', 'Bank BRI'],
            ['BOSDA', 'Sosialisasi Kurikulum', 'VA-998877', '2025-03-05', '521211', 'Jasa', 'Spanduk Sosialisasi', 250000, 'SIPLAH', 'Percetakan Sukses', 'Jl. Veteran No.9', 'Non-Tunai', '0987654321', 'Bank Mandiri']
        ];

        $sheet->fromArray($data, NULL, 'A2');

        // Sheet kedua: Petunjuk
        $guide = $excel->createSheet();
        $guide->setTitle('Petunjuk Pengisian');
        $guide->setCellValue('A1', 'PETUNJUK PENGISIAN TEMPLATE PENGELUARAN');
        $guide->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('1F4E78');
        $guide->getColumnDimension('A')->setWidth(100);

        $petunjuk = [
            "1. Jangan ubah urutan kolom di sheet 'Template Pengeluaran'.",
            "2. Isi mulai baris ke-2, baris pertama adalah header.",
            "3. Gunakan format tanggal YYYY-MM-DD.",
            "4. Kolom sumber, kodering, jenis belanja harus sesuai sistem.",
            "5. Kolom jumlah isi angka tanpa Rp/titik/koma.",
            "6. Platform hanya: SIPLAH / Non_SIPLAH.",
            "7. Pembayaran hanya: Tunai / Non-Tunai.",
            "8. Kolom rekening & bank diisi jika Non-Tunai.",
            "9. Simpan file dan upload di menu Laporan Keuangan sekolah."
        ];

        $row = 3;
        foreach ($petunjuk as $p) {
            $guide->setCellValue('A' . $row, $p);
            $guide->getStyle('A' . $row)->getAlignment()->setWrapText(true);
            $row += 2;
        }

        // Simpan ke folder templateexcelupload
        $path = FCPATH . 'assets/templateexcelupload/template_pengeluaran.xlsx';
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save($path);

        echo "<h3>✅ File template berhasil dibuat di:</h3>";
        echo "<code>$path</code><br><br>";
        echo "<a href='" . base_url('assets/templateexcelupload/template_pengeluaran.xlsx') . "' target='_blank'>Klik di sini untuk buka file</a>";
    }
}
