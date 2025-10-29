-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2025 at 08:54 PM
-- Server version: 5.7.33
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipelakor`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_anggaran_sekolah`
--

CREATE TABLE `tb_anggaran_sekolah` (
  `id` int(11) UNSIGNED NOT NULL,
  `sekolah_id` int(11) UNSIGNED NOT NULL,
  `sumber_id` int(11) UNSIGNED NOT NULL,
  `tahun_id` int(11) UNSIGNED NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tersisa` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_anggaran_sekolah`
--

INSERT INTO `tb_anggaran_sekolah` (`id`, `sekolah_id`, `sumber_id`, `tahun_id`, `jumlah`, `tersisa`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, '1000000.00', '1000000.00', '2025-10-27 20:09:47', '2025-10-27 21:09:42'),
(2, 2, 2, 1, '2000000.00', '2000000.00', '2025-10-27 21:45:50', '2025-10-27 21:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `tb_audit_log`
--

CREATE TABLE `tb_audit_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `aksi` varchar(255) DEFAULT NULL,
  `tabel` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `deskripsi` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_cabang`
--

CREATE TABLE `tb_cabang` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_cabang`
--

INSERT INTO `tb_cabang` (`id`, `nama`) VALUES
(1, 'KCD WIL I'),
(2, 'KCD WIL II'),
(3, 'KCD WIL III'),
(4, 'KCD WIL IV'),
(5, 'KCD WIL V'),
(6, 'KCD WIL VI'),
(7, 'KCD WIL VII'),
(8, 'KCD WIL VIII'),
(9, 'KCD WIL IX'),
(10, 'KCD WIL X'),
(11, 'KCD WIL XI'),
(12, 'KCD WIL XII'),
(13, 'KCD WIL XIII');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenjang`
--

CREATE TABLE `tb_jenjang` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_jenjang`
--

INSERT INTO `tb_jenjang` (`id`, `nama`) VALUES
(1, 'SD'),
(2, 'SMP'),
(3, 'SMA'),
(4, 'SMK'),
(5, 'SLB');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_belanja`
--

CREATE TABLE `tb_kategori_belanja` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kategori_belanja`
--

INSERT INTO `tb_kategori_belanja` (`id`, `nama`) VALUES
(1, 'Belanja Barang Habis Pakai'),
(2, 'Belanja Modal'),
(3, 'Belanja Jasa');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kodering`
--

CREATE TABLE `tb_kodering` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kategori_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kodering`
--

INSERT INTO `tb_kodering` (`id`, `kode`, `nama`, `kategori_id`) VALUES
(35, '5.1.02.01.01.0055', 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Pendidikan', 1),
(36, '5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover', 1),
(37, '5.1.02.03.02.0463', 'Belanja Pemeliharaan Alat Peraga-Alat Peraga Pelatihan dan Percontohan-Alat Peraga Pelatihan', 1),
(38, '5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak dan Penggandaan', 1),
(39, '5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor', 1),
(40, '5.1.02.02.01.0063', 'Belanja Kawat/Faksimili/Internet/TV Berlangganan', 1),
(41, '5.1.02.02.01.0003', 'Honorarium  Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia', 3),
(42, '5.1.02.02.04.0036', 'Belanja Sewa Kendaraan Bermotor Penumpang', 3),
(43, '5.1.02.02.04.0037', 'Belanja Sewa Kendaraan Bermotor Angkutan Barang', 3),
(44, '5.1.02.04.01.0003', 'Belanja Perjalanan Dinas Dalam Kota / Dalam Daerah', 3),
(45, '5.1.02.02.01.0013', 'Honorarium Tenaga Administrasi (Giyan rinaldi)', 3),
(46, '5.1.02.01.01.0037', 'Belanja Obat-Obat-Obatan', 1),
(47, '5.2.05.01.01.0001', 'Belanja Modal Buku Umum', 2),
(48, '5.2.05.01.01.0003', 'Belanja Modal Buku Agama', 2),
(49, '5.1.02.01.01.0001', 'Belanja Bahan-Bahan Bangunan dan Konstruksi', 1),
(50, '5.1.02.01.01.0031', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Listrik', 1),
(51, '5.1.02.02.01.0016', 'Belanja Jasa Tenaga Penanganan Prasarana dan Sarana Umum / Upah Tukang', 3),
(52, '5.2.02.05.01.0005', 'Belanja Modal Alat Kantor Lainnya', 2),
(53, '5.2.02.10.01.0002', 'Belanja Modal Personal Computer', 2),
(54, '5.1.02.01.01.0027', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Benda Pos', 1),
(55, '5.1.02.01.01.0030', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Perabot Kantor', 1),
(56, '5.1.02.02.01.0061', 'Belanja Tagihan Listrik', 3),
(57, '5.1.02.02.01.0062', 'Belanja Langganan Jurnal/Surat Kabar/Majalah', 1),
(58, '5.1.02.02.01.0009', 'Honorarium Penyelenggara Ujian', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengeluaran`
--

CREATE TABLE `tb_pengeluaran` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `sekolah_id` int(10) UNSIGNED NOT NULL,
  `sumber_anggaran_id` int(11) UNSIGNED DEFAULT NULL,
  `kegiatan` varchar(255) DEFAULT NULL,
  `kodering_id` int(10) UNSIGNED NOT NULL,
  `jenis_belanja_id` int(10) UNSIGNED DEFAULT NULL,
  `tahun_anggaran` year(4) NOT NULL,
  `tanggal` date NOT NULL,
  `uraian` text,
  `jumlah` decimal(15,2) NOT NULL,
  `platform` enum('SIPLAH','Non_SIPLAH') DEFAULT 'Non_SIPLAH',
  `nama_toko` varchar(150) DEFAULT NULL,
  `alamat_toko` text,
  `pembayaran` enum('Tunai','Non-Tunai') DEFAULT 'Tunai',
  `no_rekening` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu',
  `tahun` int(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengeluaran`
--

INSERT INTO `tb_pengeluaran` (`id`, `user_id`, `invoice_no`, `sekolah_id`, `sumber_anggaran_id`, `kegiatan`, `kodering_id`, `jenis_belanja_id`, `tahun_anggaran`, `tanggal`, `uraian`, `jumlah`, `platform`, `nama_toko`, `alamat_toko`, `pembayaran`, `no_rekening`, `nama_bank`, `bukti`, `status`, `tahun`, `created_at`) VALUES
(11, 2, '1111', 2, 1, 'sumatif', 39, 1, 2025, '2025-10-27', 'pensil', '40000.00', 'SIPLAH', 'CV Bima Indotama', 'cirebon', 'Tunai', '01223', 'BJB', NULL, 'Menunggu', 2025, '2025-10-27 04:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `tb_rekap_pembelanjaan`
--

CREATE TABLE `tb_rekap_pembelanjaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `sekolah_id` int(10) UNSIGNED NOT NULL,
  `sumber_anggaran_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` varchar(255) NOT NULL,
  `nilai_transaksi` decimal(15,2) DEFAULT '0.00',
  `jenis_belanja_id` int(10) UNSIGNED DEFAULT NULL,
  `platform` enum('SIPLAH','Non_SIPLAH') DEFAULT 'Non_SIPLAH',
  `nama_toko` varchar(150) DEFAULT NULL,
  `alamat_toko` text,
  `pembayaran` enum('Tunai','Non-Tunai') DEFAULT 'Tunai',
  `no_rekening` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_rekap_pembelanjaan`
--

INSERT INTO `tb_rekap_pembelanjaan` (`id`, `invoice_no`, `sekolah_id`, `sumber_anggaran_id`, `tanggal`, `kegiatan`, `nilai_transaksi`, `jenis_belanja_id`, `platform`, `nama_toko`, `alamat_toko`, `pembayaran`, `no_rekening`, `nama_bank`, `created_at`) VALUES
(3, '1111', 2, 1, '2025-10-26', 'sumatif', '40000.00', 1, 'SIPLAH', 'CV Bima Indotama', 'cirebon', 'Tunai', '01223', 'BJB', '2025-10-26 13:22:39'),
(7, 'Invois0989', 2, 1, '2025-02-01', 'Kegiatan Kesiswaan', '70000.00', 1, 'Non_SIPLAH', 'Toko Maju Jaya', 'Jl. Merdeka No.12', 'Tunai', '198899', 'Bank BRI', '2025-10-27 06:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sumber_anggaran`
--

CREATE TABLE `tb_sumber_anggaran` (
  `id` int(11) UNSIGNED NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_sumber_anggaran`
--

INSERT INTO `tb_sumber_anggaran` (`id`, `kode`, `nama`, `created_at`) VALUES
(1, NULL, 'BOS Reguler', '2025-10-26 20:02:17'),
(2, NULL, 'BOS Kinerja', '2025-10-26 20:02:17'),
(3, NULL, 'BOS Afirmasi', '2025-10-26 20:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tahun_anggaran`
--

CREATE TABLE `tb_tahun_anggaran` (
  `id` int(11) UNSIGNED NOT NULL,
  `tahun` int(6) NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_tahun_anggaran`
--

INSERT INTO `tb_tahun_anggaran` (`id`, `tahun`, `aktif`, `created_at`) VALUES
(1, 2025, 1, '2025-10-27 20:03:20'),
(2, 2026, 0, '2025-10-27 20:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenjang` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `no_kontrol` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('sekolah','admin') NOT NULL DEFAULT 'sekolah',
  `cabang_id` int(11) UNSIGNED DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `jenjang_id` int(11) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kepala_sekolah` varchar(100) DEFAULT NULL,
  `bendahara` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama`, `jenjang`, `email`, `no_kontrol`, `password`, `role`, `cabang_id`, `aktif`, `created_at`, `updated_at`, `jenjang_id`, `alamat`, `kepala_sekolah`, `bendahara`) VALUES
(1, 'Admin Dinas', NULL, 'admin@dinas.go.id', 'DINAS01', '$2y$10$sTZULfhsHPpEwHSOTtw.MeYaUuRlp.2DLwXe6FA2Ud.WXrWUJieKW', 'admin', NULL, 1, '2025-10-26 17:18:32', NULL, NULL, NULL, NULL, NULL),
(2, 'SDN 01 Contoh', '1', 'sdn01@sekolah.sch.id', 'SEKOLAH01', '$2y$10$3iUhZWPGFUU5WQkdl34ETuZrznWIuT.zKR1uxQaoytAqG6ZM5Up1m', 'sekolah', NULL, 1, '2025-10-26 18:05:52', NULL, 1, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_anggaran_sekolah`
--
ALTER TABLE `tb_anggaran_sekolah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sekolah_id` (`sekolah_id`),
  ADD KEY `sumber_id` (`sumber_id`),
  ADD KEY `tahun_id` (`tahun_id`);

--
-- Indexes for table `tb_audit_log`
--
ALTER TABLE `tb_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_cabang`
--
ALTER TABLE `tb_cabang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_jenjang`
--
ALTER TABLE `tb_jenjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kategori_belanja`
--
ALTER TABLE `tb_kategori_belanja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kodering`
--
ALTER TABLE `tb_kodering`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `tb_pengeluaran`
--
ALTER TABLE `tb_pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sekolah_id` (`sekolah_id`),
  ADD KEY `kodering_id` (`kodering_id`),
  ADD KEY `fk_pengeluaran_sumber_anggaran` (`sumber_anggaran_id`),
  ADD KEY `fk_pengeluaran_jenis_belanja` (`jenis_belanja_id`);

--
-- Indexes for table `tb_rekap_pembelanjaan`
--
ALTER TABLE `tb_rekap_pembelanjaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD UNIQUE KEY `invoice_no_2` (`invoice_no`),
  ADD KEY `sekolah_id` (`sekolah_id`),
  ADD KEY `jenis_belanja_id` (`jenis_belanja_id`);

--
-- Indexes for table `tb_sumber_anggaran`
--
ALTER TABLE `tb_sumber_anggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_tahun_anggaran`
--
ALTER TABLE `tb_tahun_anggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `no_kontrol` (`no_kontrol`),
  ADD KEY `fk_tb_user_jenjang` (`jenjang_id`),
  ADD KEY `fk_tb_user_cabang` (`cabang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_anggaran_sekolah`
--
ALTER TABLE `tb_anggaran_sekolah`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_audit_log`
--
ALTER TABLE `tb_audit_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_cabang`
--
ALTER TABLE `tb_cabang`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_jenjang`
--
ALTER TABLE `tb_jenjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_kategori_belanja`
--
ALTER TABLE `tb_kategori_belanja`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_kodering`
--
ALTER TABLE `tb_kodering`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tb_pengeluaran`
--
ALTER TABLE `tb_pengeluaran`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tb_rekap_pembelanjaan`
--
ALTER TABLE `tb_rekap_pembelanjaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_sumber_anggaran`
--
ALTER TABLE `tb_sumber_anggaran`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_tahun_anggaran`
--
ALTER TABLE `tb_tahun_anggaran`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_anggaran_sekolah`
--
ALTER TABLE `tb_anggaran_sekolah`
  ADD CONSTRAINT `tb_anggaran_sekolah_ibfk_1` FOREIGN KEY (`sekolah_id`) REFERENCES `tb_user` (`id`),
  ADD CONSTRAINT `tb_anggaran_sekolah_ibfk_2` FOREIGN KEY (`sumber_id`) REFERENCES `tb_sumber_anggaran` (`id`),
  ADD CONSTRAINT `tb_anggaran_sekolah_ibfk_3` FOREIGN KEY (`tahun_id`) REFERENCES `tb_tahun_anggaran` (`id`);

--
-- Constraints for table `tb_audit_log`
--
ALTER TABLE `tb_audit_log`
  ADD CONSTRAINT `tb_audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`);

--
-- Constraints for table `tb_kodering`
--
ALTER TABLE `tb_kodering`
  ADD CONSTRAINT `tb_kodering_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori_belanja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pengeluaran`
--
ALTER TABLE `tb_pengeluaran`
  ADD CONSTRAINT `fk_pengeluaran_jenis_belanja` FOREIGN KEY (`jenis_belanja_id`) REFERENCES `tb_kategori_belanja` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengeluaran_sumber_anggaran` FOREIGN KEY (`sumber_anggaran_id`) REFERENCES `tb_sumber_anggaran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_pengeluaran_ibfk_1` FOREIGN KEY (`sekolah_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pengeluaran_ibfk_2` FOREIGN KEY (`kodering_id`) REFERENCES `tb_kodering` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_rekap_pembelanjaan`
--
ALTER TABLE `tb_rekap_pembelanjaan`
  ADD CONSTRAINT `tb_rekap_pembelanjaan_ibfk_1` FOREIGN KEY (`sekolah_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_rekap_pembelanjaan_ibfk_2` FOREIGN KEY (`jenis_belanja_id`) REFERENCES `tb_kategori_belanja` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `fk_tb_user_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `tb_cabang` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tb_user_jenjang` FOREIGN KEY (`jenjang_id`) REFERENCES `tb_jenjang` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
