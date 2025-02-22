-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2025 at 06:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppdb_sd`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biaya_pendaftaran`
--

CREATE TABLE `biaya_pendaftaran` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jumlah_biaya` decimal(10,2) NOT NULL,
  `bukti_pembayaran_path` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('belum','pending','verified','rejected') DEFAULT 'belum',
  `tanggal_upload` datetime DEFAULT NULL,
  `tanggal_verifikasi` datetime DEFAULT NULL,
  `verifikator_id` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

CREATE TABLE `orang_tua` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jenis` enum('ayah','ibu','wali') DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `pendidikan` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `penghasilan_bulanan` decimal(12,2) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status_hidup` enum('hidup','meninggal') DEFAULT 'hidup',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_ulang`
--

CREATE TABLE `pendaftaran_ulang` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal_daftar_ulang` datetime DEFAULT NULL,
  `status` enum('pending','selesai','batal') DEFAULT 'pending',
  `verifikator_id` int(11) DEFAULT NULL,
  `bukti_daftar_ulang_path` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tipe` enum('umum','khusus') DEFAULT 'umum',
  `status` enum('draft','published') DEFAULT 'draft',
  `tanggal_publish` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `no_pendaftaran` varchar(20) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_panggilan` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT 'Indonesia',
  `anak_ke` int(11) DEFAULT NULL,
  `jumlah_saudara` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(3) DEFAULT NULL,
  `rw` varchar(3) DEFAULT NULL,
  `kelurahan` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(5) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `sekolah_asal` varchar(100) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `kk_path` varchar(255) DEFAULT NULL,
  `kia_path` varchar(255) DEFAULT NULL,
  `status_verifikasi` enum('pending','verified','rejected') DEFAULT 'pending',
  `status_kelulusan` enum('pending','lulus','tidak_lulus') DEFAULT 'pending',
  `catatan_verifikasi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `user_id`, `no_pendaftaran`, `nisn`, `nik`, `nama_lengkap`, `nama_panggilan`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `kewarganegaraan`, `anak_ke`, `jumlah_saudara`, `alamat`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `kode_pos`, `no_hp`, `sekolah_asal`, `foto_path`, `kk_path`, `kia_path`, `status_verifikasi`, `status_kelulusan`, `catatan_verifikasi`, `created_at`, `updated_at`) VALUES
(1, 1, 'PPDB25001', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 'Indonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, '2025-02-20 03:07:21', '2025-02-20 03:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','siswa') DEFAULT 'siswa',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'idilputra2000@gmail.com', '$2y$10$ZTeiLHirm.GWrvXBBBVkaexnQqOeSNgEWOC59zlKEO04eClEyqKqu', 'idilputra2000@gmail.com', 'siswa', 1, NULL, '2025-02-20 03:07:21', '2025-02-20 03:07:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `biaya_pendaftaran`
--
ALTER TABLE `biaya_pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `verifikator_id` (`verifikator_id`),
  ADD KEY `idx_biaya_status` (`status_pembayaran`);

--
-- Indexes for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `pendaftaran_ulang`
--
ALTER TABLE `pendaftaran_ulang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `verifikator_id` (`verifikator_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_pengumuman_status` (`status`,`tanggal_publish`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_pendaftaran` (`no_pendaftaran`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_siswa_no_pendaftaran` (`no_pendaftaran`),
  ADD KEY `idx_siswa_status` (`status_verifikasi`,`status_kelulusan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biaya_pendaftaran`
--
ALTER TABLE `biaya_pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orang_tua`
--
ALTER TABLE `orang_tua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pendaftaran_ulang`
--
ALTER TABLE `pendaftaran_ulang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `biaya_pendaftaran`
--
ALTER TABLE `biaya_pendaftaran`
  ADD CONSTRAINT `biaya_pendaftaran_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `biaya_pendaftaran_ibfk_2` FOREIGN KEY (`verifikator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD CONSTRAINT `orang_tua_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran_ulang`
--
ALTER TABLE `pendaftaran_ulang`
  ADD CONSTRAINT `pendaftaran_ulang_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ulang_ibfk_2` FOREIGN KEY (`verifikator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Table structure for table `nilai_seleksi`
CREATE TABLE `nilai_seleksi` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nilai_ujian_tulis` decimal(5,2) DEFAULT NULL,
  `nilai_wawancara` decimal(5,2) DEFAULT NULL,
  `catatan_pewawancara` text DEFAULT NULL,
  `tanggal_ujian_tulis` date DEFAULT NULL,
  `tanggal_wawancara` date DEFAULT NULL,
  `penguji_id` int(11) DEFAULT NULL,
  `pewawancara_id` int(11) DEFAULT NULL,
  `status_kelulusan` enum('pending','lulus','tidak_lulus') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes for table `nilai_seleksi`
ALTER TABLE `nilai_seleksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `penguji_id` (`penguji_id`),
  ADD KEY `pewawancara_id` (`pewawancara_id`),
  ADD KEY `idx_nilai_status` (`status_kelulusan`);

-- AUTO_INCREMENT for table `nilai_seleksi`
ALTER TABLE `nilai_seleksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Constraints for table `nilai_seleksi`
ALTER TABLE `nilai_seleksi`
  ADD CONSTRAINT `nilai_seleksi_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_seleksi_ibfk_2` FOREIGN KEY (`penguji_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `nilai_seleksi_ibfk_3` FOREIGN KEY (`pewawancara_id`) REFERENCES `users` (`id`);


  CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'danger') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);