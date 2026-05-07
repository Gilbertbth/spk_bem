-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 07, 2026 at 08:01 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_bem`
--

-- --------------------------------------------------------

--
-- Table structure for table `kandidat`
--

DROP TABLE IF EXISTS `kandidat`;
CREATE TABLE IF NOT EXISTS `kandidat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor_urut` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `visi_misi` text,
  `kepemimpinan` text,
  `pengalaman` text,
  `komunikasi` text,
  `integritas` text,
  `ipk` decimal(3,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kandidat`
--

INSERT INTO `kandidat` (`id`, `nomor_urut`, `nama`, `visi_misi`, `kepemimpinan`, `pengalaman`, `komunikasi`, `integritas`, `ipk`, `foto`) VALUES
(1, 1, 'Adelisa Oktavia Manalu', 'Visi\r\nMenjadikan organisasi sebagai wadah yang aktif, inovatif, dan aspiratif dalam meningkatkan kualitas mahasiswa yang berintegritas, berprestasi, serta mampu menciptakan lingkungan kampus yang harmonis dan progresif.\r\n\r\nMisi\r\n1. Menjalin komunikasi yang baik antara mahasiswa dan pihak kampus.\r\n2. Mendorong mahasiswa untuk aktif dalam kegiatan akademik maupun non-akademik.\r\n3. Mengembangkan program kerja yang kreatif, bermanfaat, dan sesuai kebutuhan mahasiswa.\r\n4. Menumbuhkan rasa solidaritas, kepedulian, dan kerja sama antar mahasiswa.\r\n5. Menjadi penyalur aspirasi mahasiswa secara adil dan bertanggung jawab.', 'Memiliki jiwa kepemimpinan yang bertanggung jawab, disiplin, dan mampu bekerja sama dalam tim. Aktif dalam kegiatan organisasi serta mampu berkomunikasi dengan baik kepada anggota maupun lingkungan sekitar. Mampu mengambil keputusan secara bijak dan adil dalam menyelesaikan permasalahan. Memiliki sikap tegas, terbuka terhadap kritik dan saran, serta mampu menjadi teladan yang baik bagi orang lain.', 'Bendahara di Himpunan Mahasiswa Program Studi (HMP). Bertanggung jawab dalam mengelola keuangan organisasi, menyusun laporan pemasukan dan pengeluaran dana, serta memastikan penggunaan anggaran berjalan dengan baik dan transparan. Selain itu, aktif bekerja sama dengan pengurus lain dalam mendukung pelaksanaan program kerja dan kegiatan organisasi.', 'Memiliki kemampuan komunikasi yang baik, sopan, dan mudah beradaptasi dengan lingkungan sekitar. Mampu menyampaikan pendapat dengan jelas serta mendengarkan aspirasi dan masukan dari orang lain dengan baik. Aktif menjalin kerja sama dan membangun hubungan yang positif dalam organisasi maupun kegiatan kampus.', 'Menjunjung tinggi kejujuran, tanggung jawab, dan komitmen dalam setiap tindakan. Konsisten antara perkataan dan perbuatan serta dapat diMenjunjung tinggi kejujuran, tanggung jawab, dan disiplin dalam menjalankan setiap tugas dan amanah yang diberikan. Memiliki komitmen untuk bekerja secara adil, konsisten, dan dapat dipercaya. Selalu mengutamakan etika, kerja sama, serta menjaga nama baik organisasi dan lingkungan kampus.percaya dalam menjalankan amanah.', 3.90, 'uploads/1778139540_adel.jpg'),
(2, 2, 'Afriyani Damanik', 'Visi\r\n\r\nMenjadikan organisasi sebagai wadah yang aktif, kreatif, dan aspiratif dalam mengembangkan potensi mahasiswa yang berprestasi, berkarakter, dan mampu bekerja sama demi kemajuan bersama.\r\n\r\nMisi\r\n1. Menjalin hubungan yang baik antar mahasiswa dan pihak kampus.\r\n2. Mendorong mahasiswa untuk aktif dalam kegiatan akademik maupun organisasi.\r\n3. Mengembangkan program kerja yang bermanfaat dan inovatif bagi mahasiswa.\r\n4. Menumbuhkan rasa solidaritas, kepedulian, dan tanggung jawab bersama.\r\n5. Menjadi penyalur aspirasi mahasiswa secara baik dan bertanggung jawab.', 'Memiliki jiwa kepemimpinan yang disiplin, bertanggung jawab, dan mampu bekerja sama dalam tim. Mampu berkomunikasi dengan baik, mengambil keputusan secara bijak, serta menjadi pribadi yang terbuka terhadap kritik dan saran demi mencapai tujuan bersama.', 'Pernah menjadi panitia kegiatan LDK (Latihan Dasar Kepemimpinan). Bertanggung jawab dalam membantu pelaksanaan kegiatan, bekerja sama dengan panitia lain, serta mendukung kelancaran acara agar berjalan dengan baik dan terorganisir.', 'Memiliki kemampuan komunikasi yang baik, sopan, dan mudah beradaptasi dengan lingkungan sekitar. Mampu menyampaikan pendapat dengan jelas serta menjalin hubungan yang positif dengan orang lain dalam organisasi maupun kegiatan kampus.', 'Menjunjung tinggi kejujuran, disiplin, dan tanggung jawab dalam menjalankan tugas. Memiliki komitmen untuk bekerja dengan baik, menjaga kepercayaan, serta mengutamakan etika dan kerja sama dalam organisasi.', 3.91, 'uploads/1778136839_afri.jpg'),
(4, 3, 'Gilbert Batahi Lumbantobing', 'Visi\r\n\r\nMenjadikan organisasi sebagai tempat berkumpul, bercanda, dan kadang bekerja kalau lagi semangat.\r\n\r\nMisi\r\n1. Datang rapat tepat waktu kalau tidak ketiduran.\r\n2. Menjadi Ketua yang aktif ketika ada konsumsi.\r\n3. Menjaga solidaritas demi tugas dan kepentingan bersama.\r\n4. Berusaha membuat organisasi tetap hidup walau banyak drama.', 'Memiliki jiwa kepemimpinan yang kadang muncul saat keadaan mendesak. Sering memberi arahan walaupun terkadang ikut bingung dengan arah sendiri.', 'Pernah menjadi anggota panitia dan berkontribusi dalam meramaikan suasana. Pernah juga “meminjam” mancis kawan tanpa izin dan akhirnya ketahuan.', 'Aktif berbicara dalam tongkrongan, terutama saat topik sudah tidak penting lagi. Mampu mencairkan suasana walaupun kadang terlalu banyak bercanda.', 'Masih dalam tahap pengembangan, tetapi tetap berusaha menjadi pribadi yang lebih baik setiap harinya.', 3.75, 'uploads/1778137079_gilbert.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

DROP TABLE IF EXISTS `kriteria`;
CREATE TABLE IF NOT EXISTS `kriteria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `bobot` decimal(5,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama`, `bobot`) VALUES
(1, 'Kepemimpinan', 0.2500),
(2, 'Visi & Misi', 0.2000),
(3, 'Pengalaman Organisasi', 0.1800),
(4, 'Komunikasi', 0.1200),
(5, 'Integritas', 0.1000),
(6, 'IPK', 0.1500);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

DROP TABLE IF EXISTS `penilaian`;
CREATE TABLE IF NOT EXISTS `penilaian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int NOT NULL,
  `id_kandidat` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `nilai` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_vote` (`id_mahasiswa`,`id_kandidat`,`id_kriteria`),
  KEY `id_kandidat` (`id_kandidat`),
  KEY `id_kriteria` (`id_kriteria`)
) ;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `id_mahasiswa`, `id_kandidat`, `id_kriteria`, `nilai`, `created_at`) VALUES
(1, 2, 1, 1, 70, '2026-04-22 07:03:40'),
(2, 2, 1, 2, 80, '2026-04-22 07:03:40'),
(3, 2, 1, 3, 90, '2026-04-22 07:03:40'),
(4, 2, 1, 4, 80, '2026-04-22 07:03:40'),
(5, 2, 1, 5, 80, '2026-04-22 07:03:40'),
(6, 2, 1, 6, 80, '2026-04-22 07:03:40'),
(7, 3, 2, 1, 90, '2026-04-22 07:04:48'),
(8, 3, 2, 2, 90, '2026-04-22 07:04:48'),
(9, 3, 2, 3, 90, '2026-04-22 07:04:48'),
(10, 3, 2, 4, 89, '2026-04-22 07:04:48'),
(11, 3, 2, 5, 90, '2026-04-22 07:04:48'),
(12, 3, 2, 6, 90, '2026-04-22 07:04:48'),
(13, 4, 1, 1, 90, '2026-04-22 07:11:22'),
(14, 4, 1, 2, 90, '2026-04-22 07:11:22'),
(15, 4, 1, 3, 90, '2026-04-22 07:11:22'),
(16, 4, 1, 4, 90, '2026-04-22 07:11:22'),
(17, 4, 1, 5, 90, '2026-04-22 07:11:22'),
(18, 4, 1, 6, 90, '2026-04-22 07:11:22'),
(19, 5, 1, 1, 90, '2026-04-29 01:37:03'),
(20, 5, 1, 2, 90, '2026-04-29 01:37:03'),
(21, 5, 1, 3, 90, '2026-04-29 01:37:03'),
(22, 5, 1, 4, 90, '2026-04-29 01:37:03'),
(23, 5, 1, 5, 90, '2026-04-29 01:37:03'),
(24, 5, 1, 6, 34, '2026-04-29 01:37:03'),
(25, 6, 4, 1, 90, '2026-05-07 07:12:08'),
(26, 6, 4, 2, 98, '2026-05-07 07:12:08'),
(27, 6, 4, 3, 85, '2026-05-07 07:12:08'),
(28, 6, 4, 4, 80, '2026-05-07 07:12:08'),
(29, 6, 4, 5, 90, '2026-05-07 07:12:08'),
(30, 6, 4, 6, 94, '2026-05-07 07:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `role` enum('admin','mahasiswa') NOT NULL,
  `has_voted` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `nim`, `role`, `has_voted`, `created_at`) VALUES
(1, 'admin', '$2y$10$WzcZ.1TvWGde4yLdp1RHj.dPcpsgry4Ds6liFeWs1Z2ACT8eqH/H2', 'Administrator', NULL, 'admin', 0, '2026-04-22 06:06:41'),
(2, 'gilbert', '$2y$10$20OrutlybbUgAI6KLhC0..KPJUyYAX/FS5xoKgRmhjY4rTohd9Kym', 'Gilbert Batahi Lumbantobing', '2302050074', 'mahasiswa', 1, '2026-04-22 06:15:54'),
(3, 'afriyani', '$2y$10$579um36iy6j5trmDouZHwOEgCyaPYiPhbB/O6mBJccgUipHHH9txa', 'Afriyani Damanik', '2302050016', 'mahasiswa', 1, '2026-04-22 06:31:18'),
(4, 'vincent', '$2y$10$BZvuBND/8o0ZEAOBm4huKeWO8dlUHPH8wTiCThKZDs3nsqSQJ.O26', 'Vincent Nadeak', '2302050001', 'mahasiswa', 1, '2026-04-22 07:10:51'),
(5, 'marcel', '$2y$10$1DX1w/LDvcrEgZoGRc4Ei.I8ieOq7XbUq8KQFzuNv9NSjaq1a/IK.', 'Marcel Sihombing', '2302050072', 'mahasiswa', 1, '2026-04-29 01:36:28'),
(6, 'zevanna', '$2y$10$eqqkIeFw0toMl0w1finWseg73W8jPqLHTDed7XXLKPvWvjqTRetli', 'Zevanna', '23010261718', 'mahasiswa', 1, '2026-05-07 05:57:27');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
