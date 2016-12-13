-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 05. Maret 2016 jam 12:17
-- Versi Server: 5.5.16
-- Versi PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ace2016`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_language`
--

CREATE TABLE IF NOT EXISTS `ac_language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` varchar(5) NOT NULL,
  `locale` varchar(255) NOT NULL,
  `image` varchar(64) NOT NULL,
  `directory` varchar(32) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `ac_language`
--

INSERT INTO `ac_language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `filename`, `sort_order`, `status`) VALUES
(1, 'Indonesia', 'id', 'id_ID.UTF-8,id_ID,id-id,indonesia', 'id.png', 'indonesia', 'indonesia', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_layout_content`
--

CREATE TABLE IF NOT EXISTS `ac_layout_content` (
  `layout_content_id` int(16) NOT NULL AUTO_INCREMENT,
  `layout_type_id` int(16) NOT NULL,
  `title` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `key` varchar(256) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`layout_content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=235 ;

--
-- Dumping data untuk tabel `ac_layout_content`
--

INSERT INTO `ac_layout_content` (`layout_content_id`, `layout_type_id`, `title`, `image`, `description`, `key`, `value`) VALUES
(132, 23, 'WELCOME TO', '', 'Andalas Civil Engineering (ACE) National Conference 2016', 'ACE CONFERENCE 2016', 'Andalas Civil Engineering (ACE) National Conference 2016'),
(155, 26, 'Tema &amp; Topik', '', '&lt;h4&gt;&lt;span style=&quot;box-sizing: content-box;&quot;&gt;Tema : “Resilience and managing of potential disaster in facing global change”&lt;/span&gt;&lt;/h4&gt;', '', ''),
(161, 27, '1. Earthquake and Tsunami ( Gempa dan Tsunami)', '', 'Deskripsi : Problem and solution; the facts, causes and impacts (social and economic vulnerability, structural and environmental hazards, public health, climate change, GIS and remote sensing)', '', ''),
(162, 27, '2. Flood and Water Scarcity (Banjir dan Kekeringan)', '', 'Problem and solution; the facts, causes and impacts (social and economic vulnerability, structural and environmental hazards, public health, climate change, GIS and remote sensing)', '', ''),
(163, 27, '3. Air and Water Quality ( Kualitas Air dan Udara)', '', 'Problem and solution; the facts, causes and impacts (social and economic vulnerability, structural and environmental hazards, public health, climate change, GIS and remote sensing)', '', ''),
(164, 27, '4. Landslide and Land subsidence ( Longsor dan Penurunan Lahan)', '', 'Problem and solution; the facts, causes and impacts (social and economic vulnerability, structural and environmental hazards, public health, climate change, GIS and remote sensing)', '', ''),
(165, 27, '5. Disaster Management (Pengelolaan Bencana) ; meliputi penanganan bencana pada saat', '', 'before-during-after disasters. Proses pencegahan dan penanganan secara teknis (structural measures) dan non teknis (non structural measures) seperti penegakan hukum, jalur evakuasi, early warning system dll, termasuk didalam bagian subtheme ini.', '', ''),
(181, 25, 'Maksud dan Tujuan', '', '&lt;div&gt;&lt;div style=&quot;&quot;&gt;Konferensi ini dimaksudkan untuk&amp;nbsp;memberikan kesempatan saling bertukar&amp;nbsp;pikiran dan berbagi informasi antara sesama&amp;nbsp;penelitian baik dari kalangan akademisi, lembaga&amp;nbsp;penelitian, para lulusan dan mahasiswa&amp;nbsp;pascasarjana mengenai perkembangan ilmu&amp;nbsp;sains dan teknologi secara umum.&amp;nbsp;&lt;/div&gt;&lt;div style=&quot;&quot;&gt;&lt;br&gt;&lt;/div&gt;&lt;div style=&quot;&quot;&gt;Selain itu,&amp;nbsp;konferensi ini juga diharapkan dapat menjadi&amp;nbsp;wadah untuk menjalin kerjasama antara&amp;nbsp;sesama peserta untuk kepentingan penelitian&amp;nbsp;di masa yang akan datang.&lt;/div&gt;&lt;/div&gt;', '', ''),
(182, 25, 'Peserta Konferensi', '', '&lt;p style=&quot;margin-top:-25px&quot;&gt;Peserta konferensi terdiri dari :&lt;/p&gt;\r\n\r\n\r\n&lt;blockquote style=&quot;font-size:12px&quot;&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;1. Mahasiswa S-2 dan S-3 dari semua jurusan&amp;nbsp;yang memiliki penelitian yang berkaitan&amp;nbsp;dengan tema konferensi.&lt;br&gt;&lt;/span&gt;2. Staf pengajar dari universitas dan institut&amp;nbsp;yang memiliki penelitian atau makalah yang&amp;nbsp;berkaitan dengan tema konferensi.&lt;br&gt;3. Penelitian dan praktisi dari berbagai lembaga/instansi yang ingin mendapatkan manfaat dari&amp;nbsp;kegiatan konferensi ini.Praktisi pemerintah dan swasta yang memiliki&lt;br&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;4. kebijakan dan pengalaman dalam pengelolaan bencana&lt;br&gt;&lt;/span&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;5.&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;Organisasi pemerintah dan swasta yang terlibat langsung dalam proses evakuasi selama dan sesudah terjadinya bencana&lt;br&gt;&lt;/span&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;6.&lt;/span&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;Masyarakat sebagai bagian dari stakeholder kebencanaan&lt;/span&gt;&lt;/blockquote&gt;', '', ''),
(183, 25, 'Kegiatan', '', '&lt;div style=&quot;line-height: 1.4;&quot;&gt;&lt;span style=&quot;font-weight: bold; line-height: 1.4;&quot;&gt;1. Seminar Panel&lt;/span&gt;&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4; font-size:12px&quot;&gt;Kegiatan ini merupakan seminar singkat, yang&amp;nbsp;merupakan wahana informasi dan diskusi&amp;nbsp;mengenai isu hangat dalam bidang ilmu sains&amp;nbsp;dan teknologi terkini.&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4;&quot;&gt;&lt;br&gt;&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4;&quot;&gt;&lt;span style=&quot;font-weight: bold;&quot;&gt;2. Seminar Paralel&lt;/span&gt;&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4; font-size:12px&quot;&gt;Kegiatan ini berisi presentasi tentang&amp;nbsp;penelitian dan pengalaman terbaru tentang&amp;nbsp;perkembangan ilmu dan penerapannya&amp;nbsp;sesuai tema konferensi oleh peserta&amp;nbsp;pemakalah.&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4;&quot;&gt;&lt;br&gt;&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4;&quot;&gt;&lt;span style=&quot;font-weight: bold;&quot;&gt;3. Workshop&lt;/span&gt;&lt;/div&gt;\r\n&lt;div style=&quot;line-height: 1.4; font-size:12px&quot;&gt;Kegiatan ini berupa pelatihan Base Isolation&amp;nbsp;Structures dengan topik:&amp;nbsp;&lt;/div&gt;\r\n&lt;blockquote style=&quot;font-size: 12px; line-height: 17.1429px;&quot;&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;1. Pemahaman dan&amp;nbsp;Sosialisasi Peraturan Gempa SNI 2012 &amp;nbsp; &amp;nbsp;&amp;nbsp;&lt;br&gt;&lt;/span&gt;2.&amp;nbsp;Analisis dan Desain Base Isolation System&lt;/blockquote&gt;', '', ''),
(184, 24, 'Description', '', 'ACE National Conference adalah singkatan dari Andalas Civil Engineering – National Conference, dan lahir dengan inisiatif untuk mewadahi banyaknya hasil-hasil penelitian yang belum terpublikasikan dan didiskusikan di ruang-ruang ilmiah.', '', ''),
(210, 28, 'Penerimaan Draft full-paper', '', 'sampai 25 April 2016', 'newspaper-o', 'now'),
(211, 28, 'Penerimaan full-paper', '', 'April - 25 Mei 2016', 'book', '25'),
(212, 28, 'Registrasi peserta', '', 'Juli 2016', 'sign-in', '20'),
(213, 28, 'Acara conference', '', '- 14 Agustus 2016', 'university', '13'),
(214, 28, 'Field trip', '', 'Agustus 2016', 'truck', '14'),
(216, 29, 'Lolaction &amp; Contact', '', '13-14 Agustus 2016 @ Kampus Universitas Andalas Limau Manis, Padang.', '', ''),
(222, 30, 'FORMAT PENULISAN MAKALAH', '', '&lt;p&gt;1. Kertas A4, dengan orientasi portrait;&lt;/p&gt;&lt;p&gt;2. Top margin adalah 2.5 cm, bottom margin adalah 4.5 cm, left margin adalah 3 cm, dan right margin adalah 2.5 cm;&lt;/p&gt;&lt;p&gt;3. Jenis font adalah Times New Roman;&lt;/p&gt;&lt;p&gt;4. Penulisan teks dalam bentuk single spaced and single column text;&lt;/p&gt;&lt;p&gt;5. Jangan membuat indent, tetapi buatlah spasi kosong diantara paragrap;&lt;/p&gt;&lt;p&gt;6. Buatlah no halaman di tengah bawah dengan font Times New Roman 11pt;Jangan menulis apapun pada bagian footers&lt;/p&gt;', '', 'Kertas A4, dengan orientasi portrait;Top margin adalah 2.5 cm, bottom margin adalah 4.5 cm, left margin adalah 3 cm, dan right margin adalah 2.5 cm;  ·         Jenis font adalah Times New Roman;  ·         Penulisan teks dalam bentuk single spaced and single column text;  ·         Jangan membuat indent, tetapi buatlah spasi kosong diantara paragrap;  ·         Buatlah no halaman di tengah bawah dengan font Times New Roman 11pt;Jangan menulis apapun pada bagian footers'),
(223, 30, 'Jumlah halaman', '', '&lt;span lang=&quot;IN&quot;&gt;Jumlah halaman adalah&lt;/span&gt;&lt;span lang=&quot;IN&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: Verdana, Helvetica, Arial, sans-serif; font-size: 13.3333px; line-height: 16.6667px;&quot;&gt;4&lt;/span&gt;&amp;nbsp;sampai&amp;nbsp;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: Verdana, Helvetica, Arial, sans-serif; font-size: 13.3333px; line-height: 16.6667px;&quot;&gt;6&lt;/span&gt;&amp;nbsp;&lt;span lang=&quot;IN&quot;&gt;halaman&lt;/span&gt;,&amp;nbsp;&lt;span lang=&quot;IN&quot;&gt;termasuk gambar, tabel, daftar pustaka, dan lampiran.&lt;/span&gt;', '', ''),
(224, 31, 'Dr. Jan  Jaap Brinkmann', '', 'Flood and Water Scarcity Diskusi dengan Pemerintah Lokal dan Universitas', '', 'Deltares - Belanda'),
(225, 31, 'Dr. Abdul Hakam', '', 'Lesson learn from the collapsed buildings in Padang liquefaction &amp;nbsp;prone area', '', 'Universitas Andalas'),
(232, 32, 'E-mail', '', 'your.email@gmail.com', '', ''),
(233, 32, 'Phone', '', '+628538238788', '', ''),
(234, 32, 'WEB', '', '&lt;a href=&quot;http://sipil.ft.unand.ac.id&quot; target=&quot;_blank&quot;&gt;http://sipil.ft.unand.ac.id&lt;/a&gt;', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_layout_type`
--

CREATE TABLE IF NOT EXISTS `ac_layout_type` (
  `layout_type_id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`layout_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data untuk tabel `ac_layout_type`
--

INSERT INTO `ac_layout_type` (`layout_type_id`, `name`) VALUES
(23, 'welcome'),
(24, 'deskripsi'),
(25, 'home'),
(26, 'tema'),
(27, 'topik'),
(28, 'time_line'),
(29, 'location'),
(30, 'penulisan'),
(31, 'speakers'),
(32, 'contact');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_setting`
--

CREATE TABLE IF NOT EXISTS `ac_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data untuk tabel `ac_setting`
--

INSERT INTO `ac_setting` (`setting_id`, `store_id`, `group`, `key`, `value`, `serialized`) VALUES
(1, 0, 'config', 'limit_list', '20', 0),
(18, 0, 'config', 'error_log_file', 'error.log', 0),
(21, 0, 'config', 'config_email', 'ahmadsisfo1@gmail.com', 0),
(22, 0, 'config', 'config_url', 'http://localhost/ace2016/', 0),
(23, 0, 'config', 'config_error_filename', 'error.log', 0),
(24, 0, 'config', 'config_encryption', 'a796c81ae2f4cd92e1e22cf16bf81702', 0),
(25, 0, 'config', 'config_api_id', '24', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_tracker`
--

CREATE TABLE IF NOT EXISTS `ac_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `query_string` text NOT NULL,
  `http_referer` text NOT NULL,
  `http_user_agent` text NOT NULL,
  `isbot` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `ac_tracker`
--

INSERT INTO `ac_tracker` (`id`, `ip`, `country`, `city`, `query_string`, `http_referer`, `http_user_agent`, `isbot`, `status`) VALUES
(1, '::1', '', '', '', 'currency=IDR; language=id; __atuvc=6%7C7; PHPSESSID=2o5lv6oi8iv1ph3jgq6n0uraf3', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_tracker_detail`
--

CREATE TABLE IF NOT EXISTS `ac_tracker_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `url` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=253 ;

--
-- Dumping data untuk tabel `ac_tracker_detail`
--

INSERT INTO `ac_tracker_detail` (`id`, `tracker_id`, `date`, `time`, `url`, `info`) VALUES
(1, 1, '2016-03-05', '11:29:28', 'localhost/ace2016/', ''),
(2, 1, '2016-03-05', '11:30:00', 'localhost/ace2016/', ''),
(3, 1, '2016-03-05', '11:30:12', 'localhost/ace2016/', ''),
(4, 1, '2016-03-05', '11:32:14', 'localhost/ace2016/', ''),
(5, 1, '2016-03-05', '11:33:11', 'localhost/ace2016/', ''),
(6, 1, '2016-03-05', '11:33:19', 'localhost/ace2016/', ''),
(7, 1, '2016-03-05', '11:34:23', 'localhost/ace2016/', ''),
(8, 1, '2016-03-05', '11:35:40', 'localhost/ace2016/', ''),
(9, 1, '2016-03-05', '11:35:59', 'localhost/ace2016/', ''),
(10, 1, '2016-03-05', '11:36:14', 'localhost/ace2016/', ''),
(11, 1, '2016-03-05', '11:36:31', 'localhost/ace2016/', ''),
(12, 1, '2016-03-05', '11:37:00', 'localhost/ace2016/', ''),
(13, 1, '2016-03-05', '11:37:17', 'localhost/ace2016/', ''),
(14, 1, '2016-03-05', '11:37:50', 'localhost/ace2016/', ''),
(15, 1, '2016-03-05', '11:38:17', 'localhost/ace2016/', ''),
(16, 1, '2016-03-05', '11:38:46', 'localhost/ace2016/', ''),
(17, 1, '2016-03-05', '11:40:38', 'localhost/ace2016/', ''),
(18, 1, '2016-03-05', '11:41:08', 'localhost/ace2016/', ''),
(19, 1, '2016-03-05', '11:41:30', 'localhost/ace2016/', ''),
(20, 1, '2016-03-05', '11:44:59', 'localhost/ace2016/', ''),
(21, 1, '2016-03-05', '11:45:47', 'localhost/ace2016/', ''),
(22, 1, '2016-03-05', '11:45:49', 'localhost/ace2016/', ''),
(23, 1, '2016-03-05', '11:46:10', 'localhost/ace2016/', ''),
(24, 1, '2016-03-05', '11:57:42', 'localhost/ace2016/', ''),
(25, 1, '2016-03-05', '12:01:28', 'localhost/ace2016/', ''),
(26, 1, '2016-03-05', '12:02:13', 'localhost/ace2016/', ''),
(27, 1, '2016-03-05', '12:02:35', 'localhost/ace2016/', ''),
(28, 1, '2016-03-05', '12:02:48', 'localhost/ace2016/', ''),
(29, 1, '2016-03-05', '12:03:23', 'localhost/ace2016/', ''),
(30, 1, '2016-03-05', '12:03:32', 'localhost/ace2016/', ''),
(31, 1, '2016-03-05', '12:07:28', 'localhost/ace2016/', ''),
(32, 1, '2016-03-05', '12:08:05', 'localhost/ace2016/', ''),
(33, 1, '2016-03-05', '12:08:33', 'localhost/ace2016/', ''),
(34, 1, '2016-03-05', '12:08:55', 'localhost/ace2016/', ''),
(35, 1, '2016-03-05', '12:09:07', 'localhost/ace2016/', ''),
(36, 1, '2016-03-05', '12:09:48', 'localhost/ace2016/', ''),
(37, 1, '2016-03-05', '12:15:21', 'localhost/ace2016/', ''),
(38, 1, '2016-03-05', '12:15:50', 'localhost/ace2016/', ''),
(39, 1, '2016-03-05', '12:22:18', 'localhost/ace2016/', ''),
(40, 1, '2016-03-05', '12:22:54', 'localhost/ace2016/', ''),
(41, 1, '2016-03-05', '12:23:05', 'localhost/ace2016/', ''),
(42, 1, '2016-03-05', '12:23:36', 'localhost/ace2016/', ''),
(43, 1, '2016-03-05', '12:24:54', 'localhost/ace2016/', ''),
(44, 1, '2016-03-05', '12:25:22', 'localhost/ace2016/', ''),
(45, 1, '2016-03-05', '12:25:41', 'localhost/ace2016/', ''),
(46, 1, '2016-03-05', '12:26:26', 'localhost/ace2016/', ''),
(47, 1, '2016-03-05', '12:27:12', 'localhost/ace2016/', ''),
(48, 1, '2016-03-05', '12:30:06', 'localhost/ace2016/', ''),
(49, 1, '2016-03-05', '12:31:18', 'localhost/ace2016/', ''),
(50, 1, '2016-03-05', '12:33:35', 'localhost/ace2016/', ''),
(51, 1, '2016-03-05', '12:34:01', 'localhost/ace2016/', ''),
(52, 1, '2016-03-05', '12:34:39', 'localhost/ace2016/', ''),
(53, 1, '2016-03-05', '12:42:44', 'localhost/ace2016/', ''),
(54, 1, '2016-03-05', '12:46:09', 'localhost/ace2016/', ''),
(55, 1, '2016-03-05', '12:46:30', 'localhost/ace2016/', ''),
(56, 1, '2016-03-05', '12:46:55', 'localhost/ace2016/', ''),
(57, 1, '2016-03-05', '12:47:08', 'localhost/ace2016/', ''),
(58, 1, '2016-03-05', '12:47:48', 'localhost/ace2016/', ''),
(59, 1, '2016-03-05', '12:51:11', 'localhost/ace2016/', ''),
(60, 1, '2016-03-05', '12:51:50', 'localhost/ace2016/', ''),
(61, 1, '2016-03-05', '12:53:27', 'localhost/ace2016/', ''),
(62, 1, '2016-03-05', '12:53:58', 'localhost/ace2016/', ''),
(63, 1, '2016-03-05', '12:54:20', 'localhost/ace2016/', ''),
(64, 1, '2016-03-05', '12:54:37', 'localhost/ace2016/', ''),
(65, 1, '2016-03-05', '12:54:58', 'localhost/ace2016/', ''),
(66, 1, '2016-03-05', '12:55:11', 'localhost/ace2016/', ''),
(67, 1, '2016-03-05', '12:55:20', 'localhost/ace2016/', ''),
(68, 1, '2016-03-05', '12:55:58', 'localhost/ace2016/', ''),
(69, 1, '2016-03-05', '12:56:23', 'localhost/ace2016/', ''),
(70, 1, '2016-03-05', '12:56:51', 'localhost/ace2016/', ''),
(71, 1, '2016-03-05', '12:57:31', 'localhost/ace2016/', ''),
(72, 1, '2016-03-05', '12:57:37', 'localhost/ace2016/', ''),
(73, 1, '2016-03-05', '12:58:27', 'localhost/ace2016/', ''),
(74, 1, '2016-03-05', '13:05:30', 'localhost/ace2016/', ''),
(75, 1, '2016-03-05', '13:07:32', 'localhost/ace2016/', ''),
(76, 1, '2016-03-05', '13:08:50', 'localhost/ace2016/', ''),
(77, 1, '2016-03-05', '13:09:40', 'localhost/ace2016/', ''),
(78, 1, '2016-03-05', '13:11:04', 'localhost/ace2016/', ''),
(79, 1, '2016-03-05', '13:12:16', 'localhost/ace2016/', ''),
(80, 1, '2016-03-05', '13:13:39', 'localhost/ace2016/', ''),
(81, 1, '2016-03-05', '13:15:58', 'localhost/ace2016/', ''),
(82, 1, '2016-03-05', '13:19:42', 'localhost/ace2016/', ''),
(83, 1, '2016-03-05', '13:19:59', 'localhost/ace2016/', ''),
(84, 1, '2016-03-05', '13:20:20', 'localhost/ace2016/', ''),
(85, 1, '2016-03-05', '13:20:43', 'localhost/ace2016/', ''),
(86, 1, '2016-03-05', '13:21:05', 'localhost/ace2016/', ''),
(87, 1, '2016-03-05', '13:21:27', 'localhost/ace2016/', ''),
(88, 1, '2016-03-05', '14:43:04', 'localhost/ace2016/', ''),
(89, 1, '2016-03-05', '14:43:46', 'localhost/ace2016/', ''),
(90, 1, '2016-03-05', '14:43:56', 'localhost/ace2016/', ''),
(91, 1, '2016-03-05', '14:44:23', 'localhost/ace2016/', ''),
(92, 1, '2016-03-05', '14:44:57', 'localhost/ace2016/', ''),
(93, 1, '2016-03-05', '14:45:43', 'localhost/ace2016/', ''),
(94, 1, '2016-03-05', '14:46:05', 'localhost/ace2016/', ''),
(95, 1, '2016-03-05', '14:46:21', 'localhost/ace2016/', ''),
(96, 1, '2016-03-05', '14:46:37', 'localhost/ace2016/', ''),
(97, 1, '2016-03-05', '14:46:50', 'localhost/ace2016/', ''),
(98, 1, '2016-03-05', '14:49:47', 'localhost/ace2016/', ''),
(99, 1, '2016-03-05', '14:50:40', 'localhost/ace2016/', ''),
(100, 1, '2016-03-05', '14:50:54', 'localhost/ace2016/', ''),
(101, 1, '2016-03-05', '14:52:48', 'localhost/ace2016/', ''),
(102, 1, '2016-03-05', '14:54:50', 'localhost/ace2016/', ''),
(103, 1, '2016-03-05', '14:55:46', 'localhost/ace2016/', ''),
(104, 1, '2016-03-05', '14:56:17', 'localhost/ace2016/', ''),
(105, 1, '2016-03-05', '14:57:17', 'localhost/ace2016/', ''),
(106, 1, '2016-03-05', '14:57:57', 'localhost/ace2016/', ''),
(107, 1, '2016-03-05', '15:00:52', 'localhost/ace2016/', ''),
(108, 1, '2016-03-05', '15:02:18', 'localhost/ace2016/', ''),
(109, 1, '2016-03-05', '15:03:08', 'localhost/ace2016/', ''),
(110, 1, '2016-03-05', '15:04:23', 'localhost/ace2016/', ''),
(111, 1, '2016-03-05', '15:05:37', 'localhost/ace2016/', ''),
(112, 1, '2016-03-05', '15:06:26', 'localhost/ace2016/', ''),
(113, 1, '2016-03-05', '15:07:10', 'localhost/ace2016/', ''),
(114, 1, '2016-03-05', '15:07:41', 'localhost/ace2016/', ''),
(115, 1, '2016-03-05', '15:08:04', 'localhost/ace2016/', ''),
(116, 1, '2016-03-05', '15:08:59', 'localhost/ace2016/', ''),
(117, 1, '2016-03-05', '15:11:07', 'localhost/ace2016/', ''),
(118, 1, '2016-03-05', '15:11:35', 'localhost/ace2016/', ''),
(119, 1, '2016-03-05', '15:12:13', 'localhost/ace2016/', ''),
(120, 1, '2016-03-05', '15:12:53', 'localhost/ace2016/', ''),
(121, 1, '2016-03-05', '15:16:09', 'localhost/ace2016/', ''),
(122, 1, '2016-03-05', '15:16:36', 'localhost/ace2016/', ''),
(123, 1, '2016-03-05', '15:19:33', 'localhost/ace2016/', ''),
(124, 1, '2016-03-05', '15:20:06', 'localhost/ace2016/', ''),
(125, 1, '2016-03-05', '15:20:31', 'localhost/ace2016/', ''),
(126, 1, '2016-03-05', '15:20:45', 'localhost/ace2016/', ''),
(127, 1, '2016-03-05', '15:24:33', 'localhost/ace2016/', ''),
(128, 1, '2016-03-05', '15:24:52', 'localhost/ace2016/', ''),
(129, 1, '2016-03-05', '15:32:19', 'localhost/ace2016/', ''),
(130, 1, '2016-03-05', '15:33:44', 'localhost/ace2016/', ''),
(131, 1, '2016-03-05', '15:34:04', 'localhost/ace2016/', ''),
(132, 1, '2016-03-05', '15:35:18', 'localhost/ace2016/', ''),
(133, 1, '2016-03-05', '15:35:59', 'localhost/ace2016/', ''),
(134, 1, '2016-03-05', '15:36:46', 'localhost/ace2016/', ''),
(135, 1, '2016-03-05', '15:37:21', 'localhost/ace2016/', ''),
(136, 1, '2016-03-05', '15:39:22', 'localhost/ace2016/', ''),
(137, 1, '2016-03-05', '15:40:03', 'localhost/ace2016/', ''),
(138, 1, '2016-03-05', '15:40:28', 'localhost/ace2016/', ''),
(139, 1, '2016-03-05', '15:41:05', 'localhost/ace2016/', ''),
(140, 1, '2016-03-05', '15:41:17', 'localhost/ace2016/', ''),
(141, 1, '2016-03-05', '15:42:15', 'localhost/ace2016/', ''),
(142, 1, '2016-03-05', '15:42:35', 'localhost/ace2016/', ''),
(143, 1, '2016-03-05', '15:43:47', 'localhost/ace2016/', ''),
(144, 1, '2016-03-05', '15:44:50', 'localhost/ace2016/', ''),
(145, 1, '2016-03-05', '15:45:46', 'localhost/ace2016/', ''),
(146, 1, '2016-03-05', '15:46:07', 'localhost/ace2016/', ''),
(147, 1, '2016-03-05', '15:46:23', 'localhost/ace2016/', ''),
(148, 1, '2016-03-05', '15:47:07', 'localhost/ace2016/', ''),
(149, 1, '2016-03-05', '15:47:41', 'localhost/ace2016/', ''),
(150, 1, '2016-03-05', '15:47:43', 'localhost/ace2016/', ''),
(151, 1, '2016-03-05', '15:55:58', 'localhost/ace2016/', ''),
(152, 1, '2016-03-05', '15:56:22', 'localhost/ace2016/', ''),
(153, 1, '2016-03-05', '15:56:49', 'localhost/ace2016/', ''),
(154, 1, '2016-03-05', '15:57:06', 'localhost/ace2016/', ''),
(155, 1, '2016-03-05', '15:58:10', 'localhost/ace2016/', ''),
(156, 1, '2016-03-05', '15:59:58', 'localhost/ace2016/', ''),
(157, 1, '2016-03-05', '16:00:58', 'localhost/ace2016/', ''),
(158, 1, '2016-03-05', '16:01:42', 'localhost/ace2016/', ''),
(159, 1, '2016-03-05', '16:02:49', 'localhost/ace2016/', ''),
(160, 1, '2016-03-05', '16:03:23', 'localhost/ace2016/', ''),
(161, 1, '2016-03-05', '16:07:27', 'localhost/ace2016/', ''),
(162, 1, '2016-03-05', '16:07:57', 'localhost/ace2016/', ''),
(163, 1, '2016-03-05', '16:09:52', 'localhost/ace2016/', ''),
(164, 1, '2016-03-05', '16:10:26', 'localhost/ace2016/', ''),
(165, 1, '2016-03-05', '16:10:38', 'localhost/ace2016/', ''),
(166, 1, '2016-03-05', '16:10:51', 'localhost/ace2016/', ''),
(167, 1, '2016-03-05', '16:11:00', 'localhost/ace2016/', ''),
(168, 1, '2016-03-05', '16:11:52', 'localhost/ace2016/', ''),
(169, 1, '2016-03-05', '16:15:09', 'localhost/ace2016/', ''),
(170, 1, '2016-03-05', '16:16:23', 'localhost/ace2016/', ''),
(171, 1, '2016-03-05', '16:16:57', 'localhost/ace2016/', ''),
(172, 1, '2016-03-05', '16:17:59', 'localhost/ace2016/', ''),
(173, 1, '2016-03-05', '16:18:02', 'localhost/ace2016/', ''),
(174, 1, '2016-03-05', '16:18:50', 'localhost/ace2016/', ''),
(175, 1, '2016-03-05', '16:19:49', 'localhost/ace2016/', ''),
(176, 1, '2016-03-05', '16:20:04', 'localhost/ace2016/', ''),
(177, 1, '2016-03-05', '16:22:26', 'localhost/ace2016/', ''),
(178, 1, '2016-03-05', '16:25:43', 'localhost/ace2016/', ''),
(179, 1, '2016-03-05', '16:26:25', 'localhost/ace2016/', ''),
(180, 1, '2016-03-05', '16:26:51', 'localhost/ace2016/', ''),
(181, 1, '2016-03-05', '16:27:23', 'localhost/ace2016/', ''),
(182, 1, '2016-03-05', '17:00:55', 'localhost/ace2016/', ''),
(183, 1, '2016-03-05', '17:02:56', 'localhost/ace2016/', ''),
(184, 1, '2016-03-05', '17:03:52', 'localhost/ace2016/', ''),
(185, 1, '2016-03-05', '17:13:12', 'localhost/ace2016/', ''),
(186, 1, '2016-03-05', '17:13:54', 'localhost/ace2016/', ''),
(187, 1, '2016-03-05', '17:14:37', 'localhost/ace2016/', ''),
(188, 1, '2016-03-05', '17:15:11', 'localhost/ace2016/', ''),
(189, 1, '2016-03-05', '17:17:14', 'localhost/ace2016/', ''),
(190, 1, '2016-03-05', '17:19:35', 'localhost/ace2016/', ''),
(191, 1, '2016-03-05', '17:20:19', 'localhost/ace2016/', ''),
(192, 1, '2016-03-05', '17:20:48', 'localhost/ace2016/', ''),
(193, 1, '2016-03-05', '17:21:05', 'localhost/ace2016/', ''),
(194, 1, '2016-03-05', '17:21:29', 'localhost/ace2016/', ''),
(195, 1, '2016-03-05', '17:21:46', 'localhost/ace2016/', ''),
(196, 1, '2016-03-05', '17:22:02', 'localhost/ace2016/', ''),
(197, 1, '2016-03-05', '17:22:20', 'localhost/ace2016/', ''),
(198, 1, '2016-03-05', '17:22:46', 'localhost/ace2016/', ''),
(199, 1, '2016-03-05', '17:23:30', 'localhost/ace2016/', ''),
(200, 1, '2016-03-05', '17:23:59', 'localhost/ace2016/', ''),
(201, 1, '2016-03-05', '17:24:18', 'localhost/ace2016/', ''),
(202, 1, '2016-03-05', '17:24:54', 'localhost/ace2016/', ''),
(203, 1, '2016-03-05', '17:25:05', 'localhost/ace2016/', ''),
(204, 1, '2016-03-05', '17:25:21', 'localhost/ace2016/', ''),
(205, 1, '2016-03-05', '17:25:56', 'localhost/ace2016/', ''),
(206, 1, '2016-03-05', '17:26:09', 'localhost/ace2016/', ''),
(207, 1, '2016-03-05', '17:26:24', 'localhost/ace2016/', ''),
(208, 1, '2016-03-05', '17:26:53', 'localhost/ace2016/', ''),
(209, 1, '2016-03-05', '17:27:01', 'localhost/ace2016/', ''),
(210, 1, '2016-03-05', '17:28:23', 'localhost/ace2016/', ''),
(211, 1, '2016-03-05', '17:29:05', 'localhost/ace2016/', ''),
(212, 1, '2016-03-05', '17:29:37', 'localhost/ace2016/', ''),
(213, 1, '2016-03-05', '17:30:21', 'localhost/ace2016/', ''),
(214, 1, '2016-03-05', '17:31:01', 'localhost/ace2016/', ''),
(215, 1, '2016-03-05', '17:31:33', 'localhost/ace2016/', ''),
(216, 1, '2016-03-05', '17:31:46', 'localhost/ace2016/', ''),
(217, 1, '2016-03-05', '17:31:58', 'localhost/ace2016/', ''),
(218, 1, '2016-03-05', '17:32:23', 'localhost/ace2016/', ''),
(219, 1, '2016-03-05', '17:33:19', 'localhost/ace2016/', ''),
(220, 1, '2016-03-05', '17:33:43', 'localhost/ace2016/', ''),
(221, 1, '2016-03-05', '17:33:51', 'localhost/ace2016/', ''),
(222, 1, '2016-03-05', '17:34:15', 'localhost/ace2016/', ''),
(223, 1, '2016-03-05', '17:35:11', 'localhost/ace2016/', ''),
(224, 1, '2016-03-05', '17:36:18', 'localhost/ace2016/', ''),
(225, 1, '2016-03-05', '17:37:15', 'localhost/ace2016/', ''),
(226, 1, '2016-03-05', '17:37:34', 'localhost/ace2016/', ''),
(227, 1, '2016-03-05', '17:38:39', 'localhost/ace2016/', ''),
(228, 1, '2016-03-05', '17:38:53', 'localhost/ace2016/', ''),
(229, 1, '2016-03-05', '17:39:28', 'localhost/ace2016/', ''),
(230, 1, '2016-03-05', '17:40:01', 'localhost/ace2016/', ''),
(231, 1, '2016-03-05', '17:40:27', 'localhost/ace2016/', ''),
(232, 1, '2016-03-05', '17:41:02', 'localhost/ace2016/', ''),
(233, 1, '2016-03-05', '17:41:20', 'localhost/ace2016/', ''),
(234, 1, '2016-03-05', '17:41:39', 'localhost/ace2016/', ''),
(235, 1, '2016-03-05', '17:41:59', 'localhost/ace2016/', ''),
(236, 1, '2016-03-05', '17:42:37', 'localhost/ace2016/', ''),
(237, 1, '2016-03-05', '17:42:59', 'localhost/ace2016/', ''),
(238, 1, '2016-03-05', '17:43:42', 'localhost/ace2016/', ''),
(239, 1, '2016-03-05', '17:44:16', 'localhost/ace2016/', ''),
(240, 1, '2016-03-05', '17:44:56', 'localhost/ace2016/', ''),
(241, 1, '2016-03-05', '17:46:46', 'localhost/ace2016/', ''),
(242, 1, '2016-03-05', '17:47:05', 'localhost/ace2016/', ''),
(243, 1, '2016-03-05', '17:47:49', 'localhost/ace2016/', ''),
(244, 1, '2016-03-05', '17:48:05', 'localhost/ace2016/', ''),
(245, 1, '2016-03-05', '17:48:44', 'localhost/ace2016/', ''),
(246, 1, '2016-03-05', '17:49:07', 'localhost/ace2016/', ''),
(247, 1, '2016-03-05', '17:49:27', 'localhost/ace2016/', ''),
(248, 1, '2016-03-05', '17:50:38', 'localhost/ace2016/', ''),
(249, 1, '2016-03-05', '17:52:48', 'localhost/ace2016/', ''),
(250, 1, '2016-03-05', '17:54:40', 'localhost/ace2016/', ''),
(251, 1, '2016-03-05', '17:55:40', 'localhost/ace2016/', ''),
(252, 1, '2016-03-05', '18:12:50', 'localhost/ace2016/', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_url_alias`
--

CREATE TABLE IF NOT EXISTS `ac_url_alias` (
  `url_alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`url_alias_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=852 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_user`
--

CREATE TABLE IF NOT EXISTS `ac_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `code` varchar(40) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `ac_user`
--

INSERT INTO `ac_user` (`user_id`, `user_group_id`, `username`, `password`, `firstname`, `lastname`, `email`, `image`, `code`, `ip`, `status`, `date_added`) VALUES
(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Adminis', 'trator', 'ahmadsisfo1@gmail.com', '', '', '::1', 1, '2016-03-05 04:29:21'),
(2, 1, 'rahmat', 'e10adc3949ba59abbe56e057f20f883e', 'rahmateee', 'nurfajri', 'khalidbw22@gmail.com', 'manager//sahabatdanbo.jpg', '', '', 1, '2016-02-01 17:10:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ac_user_group`
--

CREATE TABLE IF NOT EXISTS `ac_user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `ac_user_group`
--

INSERT INTO `ac_user_group` (`user_group_id`, `name`, `permission`) VALUES
(1, 'Administrator', 'a:2:{s:6:"access";a:35:{i:0;s:15:"admin/forgotten";i:1;s:11:"admin/login";i:2;s:12:"blog/article";i:3;s:11:"blog/author";i:4;s:13:"blog/category";i:5;s:12:"blog/comment";i:6;s:12:"blog/install";i:7;s:11:"blog/report";i:8;s:14:"home/dashboard";i:9;s:11:"home/header";i:10;s:11:"home/logout";i:11;s:10:"home/reset";i:12;s:14:"layout/content";i:13;s:17:"portfolio/article";i:14;s:18:"portfolio/category";i:15;s:17:"portfolio/install";i:16;s:16:"portfolio/report";i:17;s:15:"portfolio/skill";i:18;s:10:"public/404";i:19;s:14:"public/aboutus";i:20;s:14:"public/allblog";i:21;s:17:"public/comingsoon";i:22;s:14:"public/contact";i:23;s:14:"public/pricing";i:24;s:14:"public/service";i:25;s:10:"system/api";i:26;s:13:"system/banner";i:27;s:13:"system/layout";i:28;s:14:"system/setting";i:29;s:11:"system/user";i:30;s:17:"system/user_group";i:31;s:12:"tools/backup";i:32;s:15:"tools/error_log";i:33;s:17:"tools/filemanager";i:34;s:13:"tools/restore";}s:6:"modify";a:35:{i:0;s:15:"admin/forgotten";i:1;s:11:"admin/login";i:2;s:12:"blog/article";i:3;s:11:"blog/author";i:4;s:13:"blog/category";i:5;s:12:"blog/comment";i:6;s:12:"blog/install";i:7;s:11:"blog/report";i:8;s:14:"home/dashboard";i:9;s:11:"home/header";i:10;s:11:"home/logout";i:11;s:10:"home/reset";i:12;s:14:"layout/content";i:13;s:17:"portfolio/article";i:14;s:18:"portfolio/category";i:15;s:17:"portfolio/install";i:16;s:16:"portfolio/report";i:17;s:15:"portfolio/skill";i:18;s:10:"public/404";i:19;s:14:"public/aboutus";i:20;s:14:"public/allblog";i:21;s:17:"public/comingsoon";i:22;s:14:"public/contact";i:23;s:14:"public/pricing";i:24;s:14:"public/service";i:25;s:10:"system/api";i:26;s:13:"system/banner";i:27;s:13:"system/layout";i:28;s:14:"system/setting";i:29;s:11:"system/user";i:30;s:17:"system/user_group";i:31;s:12:"tools/backup";i:32;s:15:"tools/error_log";i:33;s:17:"tools/filemanager";i:34;s:13:"tools/restore";}}'),
(2, 'Pegawai', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
