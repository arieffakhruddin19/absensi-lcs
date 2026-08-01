-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 25, 2026 at 11:34 PM
-- Server version: 11.8.8-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u585733828_lcs`
--

-- --------------------------------------------------------

--
-- Table structure for table `postings`
--

CREATE TABLE `postings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul_tugas` varchar(255) NOT NULL,
  `tanggal_tugas` date DEFAULT NULL,
  `link_instagram` varchar(255) DEFAULT NULL,
  `link_facebook` varchar(255) DEFAULT NULL,
  `link_twitter` varchar(255) DEFAULT NULL,
  `link_tiktok` varchar(255) DEFAULT NULL,
  `link_youtube` varchar(255) DEFAULT NULL,
  `batas_waktu` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sumber_posting` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `postings`
--

INSERT INTO `postings` (`id`, `judul_tugas`, `tanggal_tugas`, `link_instagram`, `link_facebook`, `link_twitter`, `link_tiktok`, `link_youtube`, `batas_waktu`, `created_at`, `updated_at`, `sumber_posting`) VALUES
(11, 'Komisi IV DPR RI Dukung Penguatan Layanan Karantina', '2026-07-20', 'https://www.instagram.com/ditjen_pkh/p/Da_tdsqk-5y/', 'https://www.facebook.com/share/p/1BABwEsWdU/', 'https://x.com/ditjen_pkh/status/2079002528050778569', 'https://www.tiktok.com/@ditjen_pkh/photo/7664399772454849810?is_from_webapp=1&sender_device=pc&web_id=7610231904189646357', NULL, NULL, '2026-07-20 06:40:24', '2026-07-20 20:16:39', 'Ditjen PKH'),
(12, 'Kementan Perkuat Standar Mutu Pakan', '2026-07-20', 'https://www.instagram.com/ditjen_pkh/p/DbAP0_Tk--p/', 'https://www.facebook.com/share/p/193iBnRG4d/', 'https://x.com/ditjen_pkh/status/2079090732208709897?s=20', 'https://www.tiktok.com/@ditjen_pkh/photo/7664492431911505159?is_from_webapp=1&sender_device=pc&web_id=7610231904189646357', NULL, NULL, '2026-07-20 06:42:51', '2026-07-20 20:16:48', 'Ditjen PKH'),
(13, 'Komisi IV DPR RI Dukung Penguatan Layanan Karantina', '2026-07-20', 'https://www.instagram.com/pusvetma/p/Da_14DCE6q_/', 'https://www.facebook.com/share/p/14djjmFThYr/', 'https://x.com/pusvetma/status/2079027044709732434', 'https://www.tiktok.com/@pusvetmakementan/video/7664499123436915989', NULL, NULL, '2026-07-20 07:08:52', '2026-07-20 20:17:00', 'Pusvetma'),
(14, 'Kementan Perkuat Standar Mutu Pakan', '2026-07-20', 'https://www.instagram.com/pusvetma/p/DbAXet5k1mW/', 'https://www.facebook.com/share/p/192xgtAHFM/', 'https://x.com/pusvetma/status/2079101115044700565?s=20', 'https://www.tiktok.com/@pusvetmakementan/video/7664499306350595349', NULL, NULL, '2026-07-20 07:09:55', '2026-07-20 20:17:09', 'Pusvetma'),
(15, 'Mentan Amran Resmikan Ground Breaking Peternakan Sapi Perah Terbesar di Indonesia', '2026-07-21', 'https://www.instagram.com/ditjen_pkh/reel/DbCEwWOzrLV/', 'https://web.facebook.com/share/v/18y8sxPTi7/', 'https://x.com/ditjen_pkh/status/2079349933430489238?s=20', 'https://www.tiktok.com/@ditjen_pkh/video/7664751099181583623?is_from_webapp=1&sender_device=pc', 'https://youtube.com/shorts/uJyty4ZfgXQ?si=Tu1QXK5GCnrddi9H', NULL, '2026-07-21 12:30:36', '2026-07-21 20:47:57', 'Ditjen PKH'),
(16, 'Bangun Peternakan Sapi Perah Terbesar di Brebes, Mentan Amran: Selama Peternak Bisa Produksi Susu, Kami Tidak Akan Impor', '2026-07-21', NULL, 'https://web.facebook.com/share/p/1UusxMDEEV/', 'https://x.com/ditjen_pkh/status/2079372847714390115?s=20', NULL, NULL, NULL, '2026-07-21 14:17:15', '2026-07-21 14:17:15', 'Ditjen PKH'),
(17, 'Mentan Amran Pacu Swasembada Susu Lewat Investasi Peternakan Modern Terbesar di Indonesia', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1BZ2oiRq7p/', 'https://x.com/i/status/2079373485428916570', NULL, NULL, NULL, '2026-07-21 14:19:01', '2026-07-21 20:48:35', 'Ditjen PKH'),
(18, 'Presiden Prabowo: Indonesia Sudah Swasembada Delapan Komoditas Pangan', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1DJPe6GcDE/', 'https://x.com/i/status/2079373147154133346', NULL, NULL, NULL, '2026-07-21 14:20:29', '2026-07-21 20:48:52', 'Ditjen PKH'),
(19, 'Mentan Amran: Butuh 20 Peternakan Besar, Indonesia Bisa Swasembada Susu', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1DkFxh7pe3/', 'https://x.com/i/status/2079131092528082955', NULL, NULL, NULL, '2026-07-21 14:22:51', '2026-07-21 20:49:07', 'Ditjen PKH'),
(20, 'Mentan Amran: Butuh 20 Peternakan Besar, Indonesia Bisa Swasembada Susu', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1JByFbqAi2/', 'https://x.com/pusvetma/status/2079560960357048775', NULL, NULL, NULL, '2026-07-21 20:51:01', '2026-07-21 20:51:01', 'Pusvetma'),
(21, 'Presiden Prabowo: Indonesia Sudah Swasembada Delapan Komoditas Pangan', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1EiVfBNe9S/', 'https://x.com/pusvetma/status/2079561095040442781', NULL, NULL, NULL, '2026-07-21 20:51:42', '2026-07-21 20:51:42', 'Pusvetma'),
(22, 'Mentan Amran Pacu Swasembada Susu Lewat Investasi Peternakan Modern Terbesar di Indonesia', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1DV7XXnVeC/', 'https://x.com/pusvetma/status/2079561142125633666', NULL, NULL, NULL, '2026-07-21 20:52:39', '2026-07-21 20:52:39', 'Pusvetma'),
(23, 'Bangun Peternakan Sapi Perah Terbesar di Brebes, Mentan Amran: Selama Peternak Bisa Produksi Susu, Kami Tidak Akan Impor', '2026-07-21', NULL, 'https://www.facebook.com/share/p/1bVFSjxxBs/', 'https://x.com/pusvetma/status/2079561051025318171', NULL, NULL, NULL, '2026-07-21 20:54:19', '2026-07-21 20:54:19', 'Pusvetma'),
(24, 'Mentan Amran Resmikan GroundBreaking, Peternakan Sapi Perah Terbesar di Indonesia', '2026-07-21', 'https://www.instagram.com/pusvetma/reel/DbDpDxTsz9w/', 'https://www.facebook.com/share/p/1CotrMT29g/', 'https://x.com/pusvetma/status/2079561006460850585', 'https://www.tiktok.com/@pusvetmakementan/video/7664974989321440532', 'https://youtube.com/shorts/Lnpnrn-ik3M?feature=share', NULL, '2026-07-21 20:56:08', '2026-07-21 20:56:08', 'Pusvetma'),
(25, 'Kementan Perkuat Pengembangan Ayam Petelur di Tabalong, Dorong Swasembada Telur dan Stabilitas Pangan', '2026-07-22', NULL, 'https://www.facebook.com/share/p/14mpMLfW3ta/', NULL, NULL, NULL, NULL, '2026-07-22 14:51:39', '2026-07-22 14:51:39', 'Ditjen PKH'),
(26, 'Kementan Perkuat Kompetensi SDM Pakan, Dukung Produktivitas Peternakan dan Ketahanan Pangan di Kaltim', '2026-07-22', NULL, 'https://www.facebook.com/share/p/1PqJDPvMSg/', NULL, NULL, NULL, NULL, '2026-07-22 14:52:54', '2026-07-22 14:52:54', 'Ditjen PKH'),
(27, 'Live #JagaTernak', '2026-07-22', 'https://www.instagram.com/reel/DbFZWOXTPnO/?igsh=MThtMXl4MGZleHVjZw==', NULL, NULL, NULL, NULL, NULL, '2026-07-22 14:53:36', '2026-07-22 14:53:36', 'Ditjen PKH'),
(28, 'Ajak Pengusaha Tani Indonesia Fokus Ekspor, Wamentan Sudaryono : Jangan Berebut Proyek, Bangun Bisnis yang Besar', '2026-07-22', NULL, 'https://www.facebook.com/share/p/191vKcjwXL/', NULL, NULL, NULL, NULL, '2026-07-22 14:55:23', '2026-07-22 14:55:23', 'Ditjen PKH'),
(29, 'Mentan Amran kepada Pengusaha Tani Muda : Siap Rugi Berarti Siap Jadi Konglomerat', '2026-07-22', NULL, 'https://www.facebook.com/share/p/14kHvXNkEgD/', NULL, NULL, NULL, NULL, '2026-07-22 14:56:40', '2026-07-22 14:56:40', 'Ditjen PKH'),
(30, 'Bakal Hadir 30 Hadir ekor sapi perah di Brebes Peternak Lokal jadi Mitra', '2026-07-22', 'https://www.instagram.com/reel/DbDr6DETSdz/?igsh=MTd5ZG16anlvbzI0OQ==', 'https://www.facebook.com/share/v/1cHp3JQKPw/', 'https://x.com/i/status/2079583706357067999', 'https://vt.tiktok.com/ZSXbcSvW4/', 'https://youtube.com/shorts/g0Og_dMqX2g?si=4wfGGYjLIeZo2Dj8', NULL, '2026-07-22 14:58:57', '2026-07-22 14:58:57', 'Ditjen PKH'),
(31, 'Terinspirasi Piala Dunia, Mentan Amran Tegaskan Susu sebagai Fondasi Generasi Unggul Indonesia', '2026-07-22', NULL, 'https://www.facebook.com/share/p/1D2Aums1Ro/', NULL, NULL, NULL, NULL, '2026-07-22 15:00:35', '2026-07-22 15:00:35', 'Ditjen PKH'),
(32, 'Terinspirasi Piala Dunia, Mentan Amran Tegaskan Susu sebagai Fondasi Generasi Unggul Indonesia', '2026-07-22', NULL, 'https://www.facebook.com/share/p/198CohRLxq/', NULL, NULL, NULL, NULL, '2026-07-23 08:31:01', '2026-07-23 08:31:01', 'Pusvetma'),
(33, 'Bakal Hadir 30 Hadir ekor sapi perah di Brebes Peternak Lokal jadi Mitra', '2026-07-22', 'https://www.instagram.com/pusvetma/reel/DbDvZlnMcqa/', 'https://www.facebook.com/share/p/1D5J9fPkQb/', 'https://x.com/pusvetma/status/2080095326061629646', 'https://www.tiktok.com/@pusvetmakementan/video/7664988700102626568', 'https://youtube.com/shorts/Roq1oVlsxVs?feature=share', NULL, '2026-07-23 08:32:09', '2026-07-23 08:36:39', 'Pusvetma'),
(34, 'Mentan Amran kepada Pengusaha Tani Muda : Siap Rugi Berarti Siap Jadi Konglomerat', '2026-07-22', NULL, 'https://www.facebook.com/share/p/1Bv2EaYKpw/', NULL, NULL, NULL, NULL, '2026-07-23 08:32:35', '2026-07-23 08:36:33', 'Pusvetma'),
(35, 'Ajak Pengusaha Tani Indonesia Fokus Ekspor, Wamentan Sudaryono : Jangan Berebut Proyek, Bangun Bisnis yang Besar', '2026-07-22', NULL, 'https://www.facebook.com/share/p/14qzBhKJmDA/', NULL, NULL, NULL, NULL, '2026-07-23 08:33:09', '2026-07-23 08:36:27', 'Pusvetma'),
(36, 'Bangkit dari kegagalan intip rahasia sukses peternak ayam petelur', '2026-07-22', 'https://www.instagram.com/pusvetma/reel/DbHbpoUMN8i/', NULL, 'https://x.com/pusvetma/status/2080095367547547685?s=20', 'https://www.tiktok.com/@pusvetmakementan/video/7665522213067427093', 'https://youtu.be/VbxspsOn1BQ', NULL, '2026-07-23 08:34:42', '2026-07-23 08:36:20', 'Pusvetma'),
(37, 'Kementan Perkuat Kompetensi SDM Pakan, Dukung Produktivitas Peternakan dan Ketahanan Pangan di Kaltim', '2026-07-22', NULL, 'https://www.facebook.com/share/p/1LUrmxgzeE/', NULL, NULL, NULL, NULL, '2026-07-23 08:35:43', '2026-07-23 08:35:43', 'Pusvetma'),
(38, 'Kementan Perkuat Pengembangan Ayam Petelur di Tabalong, Dorong Swasembada Telur dan Stabilitas Pangan', '2026-07-22', NULL, 'https://www.facebook.com/share/p/1D9T5n8Gwk/', NULL, NULL, NULL, NULL, '2026-07-23 08:36:13', '2026-07-23 08:36:13', 'Pusvetma'),
(39, 'Kesejahteraan Hewan Jadi Fondasi Peternakan Berkelanjutan, Kementan Perkuat Kolaborasi Internasional', '2026-07-23', NULL, 'https://www.facebook.com/share/p/1D6pVucEc4/', NULL, NULL, NULL, NULL, '2026-07-23 14:41:16', '2026-07-23 14:41:16', 'Ditjen PKH'),
(40, 'Kementan Perkuat Laboratorium Genomik untuk Lindungi Peternak dari Ancaman Penyakit Hewan', '2026-07-23', NULL, 'https://www.facebook.com/share/p/18t9eDJa6c/', 'https://x.com/i/status/2080149189431013805', NULL, NULL, NULL, '2026-07-23 14:41:50', '2026-07-23 14:41:50', 'Ditjen PKH'),
(41, 'Selamat Hari Anak Nasional', '2026-07-23', 'https://www.instagram.com/p/DbHZNPpzEQ9/?igsh=MWNjamZ6dWxhMmgzcA==', 'https://www.facebook.com/share/p/1BWadWTUx4/', 'https://x.com/i/status/2080093619177091347', 'https://vt.tiktok.com/ZSXG28CRb/', 'http://youtube.com/post/UgkxlU0KOY33qtiZ0IAShoDxq1t6Y8ixU37y?si=ZkyTTRZ7W43K1YlD', NULL, '2026-07-23 14:42:48', '2026-07-23 14:42:48', 'Ditjen PKH'),
(42, 'Kementan Perketat Pengawasan Impor Pakan, Barantin Musnahkan 312 Ton MBM Terkontaminasi Porcine', '2026-07-23', NULL, 'https://www.facebook.com/share/p/1D323hzu3g/', NULL, NULL, NULL, NULL, '2026-07-23 14:43:09', '2026-07-23 14:43:09', 'Ditjen PKH'),
(43, 'Jelang Dilantik sebagai Kepala BGN, Sudaryono Pastikan Sinergi MBG dan Pertanian Perkuat Kesejahteraan Petani', '2026-07-23', NULL, 'https://www.facebook.com/share/p/17haSJmTps/', NULL, NULL, NULL, NULL, '2026-07-23 14:44:41', '2026-07-23 14:44:41', 'Ditjen PKH'),
(44, 'Selamat dan sukses atas dilantiknya Dr. Sudaryono, B. Eng., M.M., MBA sebagai Kepala Badan Gizi Nasional', '2026-07-23', 'https://www.instagram.com/p/DbFqBiQkXt0/?igsh=MTI3aDAzZ21nbmd0bA==', 'https://www.facebook.com/share/p/1EL7x6wfyh/', 'https://x.com/i/status/2079849737402872209', 'https://vt.tiktok.com/ZSXpry5qt/', 'http://youtube.com/post/UgkxPxDMDUiLeTrqrZJRW4oQfjDqPQvys3vI?si=HWIqMZIJ1Vv9wEHq', NULL, '2026-07-23 14:45:30', '2026-07-23 14:45:30', 'Ditjen PKH'),
(45, 'Kementan Perketat Pengawasan Impor Pakan, Barantan Musnahkan 312 Ton MBM Terkontaminasi Porcine', '2026-07-24', 'https://www.instagram.com/reel/DbKi-uKzSxB/?igsh=dTdmaWxqaWtoNzNl', NULL, 'https://x.com/i/status/2080545531873697808', 'https://vt.tiktok.com/ZSXWmXrMB/', 'https://youtube.com/shorts/n4k3ZEaCpvM?si=S5BDUPsG8cvLtFbH', NULL, '2026-07-24 14:37:26', '2026-07-24 14:37:26', 'Ditjen PKH'),
(46, 'Live #JagaTernak', '2026-07-24', 'https://www.instagram.com/reel/DbKArILT2wB/?igsh=MWR4YzkyZ3J6YW9xNQ==', NULL, 'https://x.com/i/status/2080450852448514083', 'https://vt.tiktok.com/ZSXWUSabS/', 'https://youtu.be/zSn82wSozwo?si=R7yNfIFsxAqudjd_', NULL, '2026-07-24 14:38:08', '2026-07-24 14:38:08', 'Ditjen PKH'),
(47, 'Kementan Perkuat Layanan Sertifikasi Benih dan Bibit Ternak melalui Peningkatan Kompetensi Auditor', '2026-07-24', NULL, 'https://www.facebook.com/share/p/19WawqWc2N/', 'https://x.com/i/status/2080498838352343144', NULL, NULL, NULL, '2026-07-24 14:38:40', '2026-07-24 14:38:40', 'Ditjen PKH'),
(48, 'Kementan Dorong Usaha Pembibitan Semakin Profesional, Jawab Tantangan Industri Perunggasan', '2026-07-24', NULL, 'https://www.facebook.com/share/p/1K2BgvKkEk/', 'https://x.com/i/status/2080233567603540345', NULL, NULL, NULL, '2026-07-24 14:39:16', '2026-07-24 14:39:16', 'Ditjen PKH'),
(49, 'Kunjungan Resmi Delegasi Republik Belarus Ke Ditjen PKH', '2026-07-24', NULL, 'https://www.facebook.com/share/p/1EFw32vv79/', NULL, NULL, NULL, NULL, '2026-07-24 14:39:36', '2026-07-24 14:39:36', 'Ditjen PKH'),
(50, 'Kementan Optimis Wujudkan Swasembada Susu, Industri Sapi Perah Terbesar di Indonesia Siap Dibangun', '2026-07-24', 'https://www.instagram.com/reel/DbIOderxEbV/?igsh=MW41MjgxcHd3YzFjeQ==', 'https://www.facebook.com/share/r/1MUD8F5FnV/', 'https://x.com/i/status/2080450852448514083', 'https://vt.tiktok.com/ZSXWUjfQm/', 'https://youtu.be/Lp7_QfVrsEE?si=_a3m0U12liAv5wzv', NULL, '2026-07-24 14:40:23', '2026-07-24 14:40:23', 'Ditjen PKH'),
(51, 'Indonesia - Belarus Perkuat Kemitraan Akses Pasar Lebih Luas Untuk Produk Peternakan Indonesia', '2026-07-25', 'https://www.instagram.com/p/DbMlce3k6t_/?igsh=MXduOWU1b3pmNDdyZg==', 'https://www.facebook.com/share/p/1HJoPrE2YZ/', 'https://x.com/i/status/2080793759567225064', 'https://vt.tiktok.com/ZSXcCjBtP/', 'http://youtube.com/post/Ugkxs8pvUWcj0vRY9fUkdNV1hKz6O9ceohI8?si=8RPaAlkbMdZ1lYxy', NULL, '2026-07-25 15:32:05', '2026-07-25 15:32:05', 'Ditjen PKH'),
(52, 'Kementan Perkuat penyediaan Pakan Ternak Pondasi Ketahanan Pangan Nasional', '2026-07-25', 'https://www.instagram.com/reel/DbK-44cTgwD/?igsh=OHJ1OGpyZWNrNmE4', NULL, 'https://x.com/i/status/2080595305557569913', 'https://vt.tiktok.com/ZSXcQTofa/', 'https://youtube.com/shorts/Ytdhb0Abv80?si=lzVtCfvFze4JrPr8', NULL, '2026-07-25 15:33:01', '2026-07-25 15:33:01', 'Ditjen PKH'),
(53, 'Indonesia - Belarus Perkuat Kemitraan Akses Pasar Lebih Luas Untuk Produk Peternakan Indonesia', '2026-07-25', 'https://www.instagram.com/pusvetma/p/DbNaqaaEyyJ/', 'https://www.facebook.com/share/p/15zWHjBna6N/', 'https://x.com/pusvetma/status/2080936014823280738', 'https://www.tiktok.com/@pusvetmakementan/video/7666383705958518037', 'http://youtube.com/post/UgkxwQVlJhJptAcnLJa-2UEXwUi_D2wxZml9?si=qTsRoidszASCBE7k', NULL, '2026-07-25 15:50:15', '2026-07-25 15:50:15', 'Pusvetma'),
(54, 'Kementan Perkuat penyediaan Pakan Ternak Pondasi Ketahanan Pangan Nasional', '2026-07-25', 'https://www.instagram.com/pusvetma/reel/DbNZ5eMsfei/', NULL, 'https://x.com/pusvetma/status/2080935968316817577?s=20', 'https://www.tiktok.com/@pusvetmakementan/video/7666383165652618517', 'https://youtube.com/shorts/wxHS9fLcAhw?feature=share', NULL, '2026-07-25 15:51:48', '2026-07-25 15:51:48', 'Pusvetma');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `postings`
--
ALTER TABLE `postings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `postings`
--
ALTER TABLE `postings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
