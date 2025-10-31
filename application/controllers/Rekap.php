<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Rekap_model', 'Pengeluaran_model', 'Tahun_model']);
        $this->load->library('session');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // 🔹 Halaman daftar rekap untuk sekolah (user)
   public function index()
{
    if ($this->session->userdata('role') != 'sekolah') {
        redirect('rekap/admin');
    }

    $this->load->model('Rekap_model');
    $this->load->model('Tahun_model');

    $tahun_id = $this->input->get('tahun_id') ?: $this->session->userdata('tahun_id');
    $data['tahun_all'] = $this->Tahun_model->get_all();
    $data['rekap'] = $this->Rekap_model->get_by_sekolah($this->session->userdata('user_id'), $tahun_id);

    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $data['tahun_aktif'] = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

    $this->db->select_sum('nilai_transaksi', 'total_penggunaan');
    $this->db->where('sekolah_id', $this->session->userdata('user_id'));
    $this->db->where('YEAR(tanggal)', $data['tahun_aktif']);
    $total_row = $this->db->get('tb_rekap_pembelanjaan')->row();
    $data['total_penggunaan'] = $total_row ? $total_row->total_penggunaan : 0;

    // 🔹 view sekolah
    $this->load->view('template/header');
    $this->load->view('template/sidebar_sekolah');
    $this->load->view('sekolah/rekap_list', $data);
    $this->load->view('template/footer');
}
// public function detail($invoice_no)
// {
// ==========================================================
// 🔹 EXPORT EXCEL (masih sama seperti sebelumnya)
// ==========================================================
public function export_excel()
{
    $tahun_id   = $this->input->get('tahun_id');
    $bulan      = $this->input->get('bulan');
    $sekolah_id = $this->input->get('sekolah_id');

    // Ambil tahun aktif
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

    // Ambil data berdasarkan filter
    $this->db->select('r.*, u.nama as nama_sekolah, u.alamat as kecamatan, kb.nama as jenis_belanja');
    $this->db->from('tb_rekap_pembelanjaan r');
    $this->db->join('tb_user u', 'r.sekolah_id = u.id', 'left');
    $this->db->join('tb_kategori_belanja kb', 'r.jenis_belanja_id = kb.id', 'left');

    // Filter tahun + handle data tanpa tanggal
    $this->db->group_start();
    $this->db->where('YEAR(r.tanggal)', $tahun);
    $this->db->or_where('r.tanggal IS NULL', null, false);
    $this->db->group_end();

    if (!empty($bulan)) {
        $this->db->where('MONTH(r.tanggal)', $bulan);
    }

    if (!empty($sekolah_id)) {
        $this->db->where('r.sekolah_id', $sekolah_id);
    }

    $this->db->order_by('r.tanggal', 'DESC');
    $rekap = $this->db->get()->result();

    // Load PHPExcel
    $this->load->library('PHPExcel_lib');
    $excel = new PHPExcel();
    $excel->getProperties()->setCreator('Sipelakor')
           ->setTitle('Rekap Pembelanjaan Sekolah Tahun ' . $tahun);

    $sheet = $excel->setActiveSheetIndex(0);
    $sheet->setTitle('Rekap Pembelanjaan');

    // === Header Judul ===
    $sheet->mergeCells('A1:M1');
    $sheet->setCellValue('A1', 'Rekap Pembelanjaan Sekolah Tahun ' . $tahun);
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $row = 3;
    $colNames = [
        'A' => 'No',
        'B' => 'Nama Sekolah',
        'C' => 'Kecamatan',
        'D' => 'Tanggal Transaksi',
        'E' => 'Nilai Transaksi',
        'F' => 'Jenis Belanja',
        'G' => 'Platform',
        'H' => 'Marketplace',
        'I' => 'Nama Toko',
        'J' => 'Alamat Toko',
        'K' => 'Pembayaran',
        'L' => 'No Rekening / Virtual Account',
        'M' => 'Nama Bank'
    ];

    // === Set Header Kolom ===
    foreach ($colNames as $col => $title) {
        $sheet->setCellValue($col . $row, $title);
        $sheet->getStyle($col . $row)->getFont()->setBold(true);
        $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getStyle($col . $row)->applyFromArray([
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9E1F2']
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ]);
    }

    // === Isi Data ===
    $no = 1;
    $row++;
    $total = 0;
    foreach ($rekap as $r) {
        $sheet->setCellValue("A$row", $no++);
        $sheet->setCellValue("B$row", $r->nama_sekolah);
        $sheet->setCellValue("C$row", $r->kecamatan);
        $sheet->setCellValue("D$row", $r->tanggal);
        $sheet->setCellValue("E$row", 'Rp ' . number_format($r->nilai_transaksi, 0, ',', '.'));
        $sheet->setCellValue("F$row", $r->jenis_belanja);
        $sheet->setCellValue("G$row", $r->platform);
        $sheet->setCellValue("H$row", $r->marketplace);
        $sheet->setCellValue("I$row", $r->nama_toko);
        $sheet->setCellValue("J$row", $r->alamat_toko);
        $sheet->setCellValue("K$row", $r->pembayaran);
        $sheet->setCellValueExplicit("L$row", $r->no_rekening, PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->setCellValue("M$row", $r->nama_bank);

        $total += (float)$r->nilai_transaksi;

        foreach (range('A', 'M') as $col) {
            $sheet->getStyle("$col$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle("$col$row")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }

        $row++;
    }

    // === Baris Total ===
    $sheet->setCellValue("D$row", "TOTAL");
    $sheet->setCellValue("E$row", 'Rp ' . number_format($total, 0, ',', '.'));
    $sheet->getStyle("D$row:E$row")->getFont()->setBold(true);
    $sheet->getStyle("D$row:E$row")->applyFromArray([
        'borders' => [
            'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
        ]
    ]);

    // === Styling Umum ===
    $sheet->getStyle('A3:M' . ($row))->applyFromArray([
        'alignment' => [
            'wrapText' => true
        ]
    ]);

    // === Output File ===
    if (ob_get_length()) ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Rekap_Pembelanjaan_' . $tahun . '.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save('php://output');
    exit;
}

// ==========================================================
// 🔹 EXPORT PDF (PAKAI TCPDF, kompatibel PHP 5.6)
// ==========================================================
public function export_pdf()
{
    $tahun_id   = $this->input->get('tahun_id');
    $bulan      = $this->input->get('bulan');
    $sekolah_id = $this->input->get('sekolah_id');

    // 🔹 Ambil data tahun
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');

    // 🔹 Ambil data rekap sesuai filter
    $this->db->select('r.*, u.nama as nama_sekolah, u.alamat as kecamatan, kb.nama as jenis_belanja');
    $this->db->from('tb_rekap_pembelanjaan r');
    $this->db->join('tb_user u', 'r.sekolah_id = u.id', 'left');
    $this->db->join('tb_kategori_belanja kb', 'r.jenis_belanja_id = kb.id', 'left');
    $this->db->where('YEAR(r.tanggal)', $tahun);

    if (!empty($bulan)) {
        $this->db->where('MONTH(r.tanggal)', $bulan);
    }
    if (!empty($sekolah_id)) {
        $this->db->where('r.sekolah_id', $sekolah_id);
    }

    $rekap = $this->db->order_by('r.tanggal', 'DESC')->get()->result();

    // 🔹 Load TCPDF LEGAL LANDSCAPE
    $this->load->library('tcpdf');
    $pdf = new TCPDF('L', 'mm', 'LEGAL', true, 'UTF-8', false);
    $pdf->SetCreator('Sipelakor');
    $pdf->SetTitle('Rekap Pembelanjaan Sekolah Tahun ' . $tahun);
    $pdf->SetMargins(4, 8, 4);
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 10); // ✅ Ukuran font seimbang

    // 🔹 Header dokumen
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Rekap Pembelanjaan Sekolah Tahun ' . $tahun, 0, 1, 'C');

    if (!empty($bulan)) {
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 7, 'Periode: ' . date('F', mktime(0,0,0,$bulan,1)), 0, 1, 'C');
    }

    if (!empty($sekolah_id)) {
        $sekolah = $this->db->get_where('tb_user', ['id' => $sekolah_id])->row();
        if ($sekolah) {
            $pdf->Cell(0, 7, 'Sekolah: ' . $sekolah->nama, 0, 1, 'C');
        }
    }

    $pdf->Ln(5);

    // 🔹 Header Tabel
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(220, 230, 241);

    // ✅ Lebar kolom pas Legal (355mm) tanpa terpotong kanan
    $widths = [10, 32, 27, 23, 30, 25, 27, 28, 30, 33, 25, 33, 27];
    $headers = [
        'No', 'Nama Sekolah', 'Kecamatan', 'Tanggal', 'Nilai Transaksi',
        'Jenis Belanja', 'Platform', 'Marketplace', 'Nama Toko', 'Alamat Toko',
        'Pembayaran', 'No Rekening / VA', 'Nama Bank'
    ];

    // 🔹 Hitung total lebar tabel untuk verifikasi (opsional debugging)
    // $pdf->Cell(0, 8, 'Total width: '.array_sum($widths), 0, 1);

    foreach ($headers as $i => $header) {
        $pdf->MultiCell($widths[$i], 11, $header, 1, 'C', true, 0, '', '', true, 0, false, true, 11, 'M');
    }
    $pdf->Ln();

    // 🔹 Isi tabel
    $pdf->SetFont('helvetica', '', 9.5);
    $total = 0;
    $no = 1;

    foreach ($rekap as $r) {
        $row = [
            $no++,
            $r->nama_sekolah,
            $r->kecamatan,
            date('d/m/Y', strtotime($r->tanggal)),
            'Rp ' . number_format($r->nilai_transaksi, 0, ',', '.'),
            $r->jenis_belanja,
            $r->platform,
            $r->marketplace,
            $r->nama_toko,
            $r->alamat_toko,
            $r->pembayaran,
            $r->no_rekening,
            $r->nama_bank
        ];

        $total += (float)$r->nilai_transaksi;

        foreach ($row as $i => $cell) {
            $align = ($i == 0 || $i == 3 || $i == 10) ? 'C' : (($i == 4) ? 'R' : 'L');
            $pdf->MultiCell($widths[$i], 10, $cell ?: '-', 1, $align, false, 0, '', '', true, 0, false, true, 10, 'M');
        }
        $pdf->Ln();
    }

    // 🔹 Baris total bawah
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(array_sum(array_slice($widths, 0, 4)), 10, 'TOTAL', 1, 0, 'R', true);
    $pdf->Cell($widths[4], 10, 'Rp ' . number_format($total, 0, ',', '.'), 1, 0, 'R', true);
    $pdf->Cell(array_sum(array_slice($widths, 5)), 10, '', 1, 1, 'C', true);

    // 🔹 Footer
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->Cell(0, 7, 'Dicetak pada: ' . date('d/m/Y H:i') . ' | Sistem Pelaporan Keuangan Organisasi', 0, 1, 'R');

    // ✅ Output
    $pdf->Output('Rekap_Pembelanjaan_' . $tahun . '.pdf', 'I');
}



public function detail()
{
    $invoice_no = $this->input->get('invoice_no');
// Pastikan model sudah diload
    $this->load->model('Rekap_model');
    $this->load->model('Pengeluaran_model');

    // Ambil data rekap dan pengeluaran berdasarkan invoice
    $data['rekap'] = $this->Rekap_model->get($invoice_no);
    $data['pengeluaran'] = $this->Pengeluaran_model->get_by_invoice($invoice_no);

    // Jika invoice tidak ditemukan → tampilkan 404
    if (!$data['rekap']) {
        show_404();
        return;
    }

    // Pilih layout berdasarkan role
    $role = strtolower($this->session->userdata('role'));

    // Header selalu sama
    $this->load->view('template/header');

    if ($role == 'admin') {
        $this->load->view('template/sidebar_admin');
    } else {
        $this->load->view('template/sidebar_sekolah');
    }

    // Load halaman detail
    $this->load->view('rekap/detail', $data);
    $this->load->view('template/footer');
}

    // 🔹 Halaman daftar rekap untuk admin
    public function admin()
{
    if ($this->session->userdata('role') != 'admin') {
        show_error('Akses ditolak.', 403);
    }

    $tahun_id   = $this->input->get('tahun_id') ?: $this->session->userdata('tahun_id');
    $bulan      = $this->input->get('bulan');
    $sekolah_id = $this->input->get('sekolah_id');

    $data['tahun_all'] = $this->Tahun_model->get_all();
    $data['sekolah_all'] = $this->db->get_where('tb_user', ['role' => 'sekolah'])->result();

    // Ambil tahun angka
    $tahun_row = $this->db->get_where('tb_tahun_anggaran', ['id' => $tahun_id])->row();
    $tahun = isset($tahun_row->tahun) ? $tahun_row->tahun : date('Y');
    $data['tahun_aktif'] = $tahun;

    // Query utama
    $this->db->select('r.*, u.nama as nama_sekolah, u.alamat as kecamatan, kb.nama as jenis_belanja');
    $this->db->from('tb_rekap_pembelanjaan r');
    $this->db->join('tb_user u', 'r.sekolah_id = u.id', 'left');
    $this->db->join('tb_kategori_belanja kb', 'r.jenis_belanja_id = kb.id', 'left');
    $this->db->where('YEAR(r.tanggal)', $tahun);

    if (!empty($bulan)) {
        $this->db->where('MONTH(r.tanggal)', $bulan);
    }

    if (!empty($sekolah_id)) {
        $this->db->where('r.sekolah_id', $sekolah_id);
    }

    $this->db->order_by('r.tanggal', 'DESC');
    $data['rekap'] = $this->db->get()->result();

    // Hitung total
    $this->db->select_sum('nilai_transaksi', 'total_penggunaan');
    $this->db->where('YEAR(tanggal)', $tahun);
    if (!empty($bulan)) {
        $this->db->where('MONTH(tanggal)', $bulan);
    }
    if (!empty($sekolah_id)) {
        $this->db->where('sekolah_id', $sekolah_id);
    }
    $total_row = $this->db->get('tb_rekap_pembelanjaan')->row();
    $data['total_penggunaan'] = $total_row ? $total_row->total_penggunaan : 0;

    // View
    $this->load->view('template/header');
    $this->load->view('template/sidebar_admin');
    $this->load->view('admin/rekap_list', $data);
    $this->load->view('template/footer');
}


    // 🔹 Tambah data rekap (hanya sekolah)
    public function tambah() {
        if ($this->session->userdata('role') != 'sekolah') show_error('Akses ditolak.', 403);

        $this->load->model('Kategori_belanja_model');

        if ($this->input->post()) {
            $data = [
                'invoice_no' => $this->Rekap_model->generate_invoice(),
                'sekolah_id' => $this->session->userdata('user_id'),
                'tanggal' => $this->input->post('tanggal'),
                'kegiatan' => $this->input->post('kegiatan'),
                'jenis_belanja_id' => $this->input->post('jenis_belanja_id'),
                'platform' => $this->input->post('platform'),
                'nama_toko' => $this->input->post('nama_toko'),
                'alamat_toko' => $this->input->post('alamat_toko'),
                'pembayaran' => $this->input->post('pembayaran'),
                'no_rekening' => $this->input->post('no_rekening'),
                'nama_bank' => $this->input->post('nama_bank'),
            ];
            $this->Rekap_model->insert($data);
            redirect('pengeluaran');
        }

        $data['kategori'] = $this->Kategori_belanja_model->get_all();

        $this->load->view('template/header');
        $this->load->view('template/sidebar_sekolah');
        $this->load->view('sekolah/rekap_tambah', $data);
        $this->load->view('template/footer');
    }
    
}

 

