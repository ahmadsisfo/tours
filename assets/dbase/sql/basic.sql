-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 25. Februari 2016 jam 08:04
-- Versi Server: 5.5.16
-- Versi PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fengine`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ay_alamat`
--

--
-- Struktur dari tabel `ay_setting`
--

CREATE TABLE IF NOT EXISTS `ay_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data untuk tabel `ay_setting`
--

INSERT INTO `ay_setting` (`setting_id`, `store_id`, `group`, `key`, `value`, `serialized`) VALUES
(1, 0, 'config', 'limit_list', '20', 0),
(16, 0, 'config', 'config_email', 'ahmadsisfo1@gmail.com', 0),
(17, 0, 'config', 'config_url', 'http://localhost/rnf/', 0),
(18, 0, 'config', 'error_log_file', 'error.log', 0),
(19, 0, 'config', 'config_encryption', 'c9fdb4041d98e08517a37553ff711bbb', 0),
(20, 0, 'config', 'config_api_id', '5', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ay_user`
--

CREATE TABLE IF NOT EXISTS `ay_user` (
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
-- Dumping data untuk tabel `ay_user`
--

INSERT INTO `ay_user` (`user_id`, `user_group_id`, `username`, `password`, `firstname`, `lastname`, `email`, `image`, `code`, `ip`, `status`, `date_added`) VALUES
(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Adminis', 'trator', 'ahmadsisfo1@gmail.com', 'manager//Puspa.jpg', '', '::1', 1, '2015-12-21 03:00:26'),
(2, 1, 'rahmat', 'e10adc3949ba59abbe56e057f20f883e', 'rahmateee', 'nurfajri', 'khalidbw22@gmail.com', 'manager//sahabatdanbo.jpg', '', '', 1, '2016-02-01 17:10:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ay_user_group`
--

CREATE TABLE IF NOT EXISTS `ay_user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `ay_user_group`
--

INSERT INTO `ay_user_group` (`user_group_id`, `name`, `permission`) VALUES
(1, 'Administrator', 'a:2:{s:6:"access";a:15:{i:0;s:14:"home/dashboard";i:1;s:11:"home/header";i:2;s:11:"home/logout";i:3;s:10:"home/reset";i:4;s:19:"kepegawaian/pegawai";i:5;s:10:"system/api";i:6;s:13:"system/banner";i:7;s:13:"system/layout";i:8;s:14:"system/setting";i:9;s:11:"system/user";i:10;s:17:"system/user_group";i:11;s:12:"tools/backup";i:12;s:15:"tools/error_log";i:13;s:17:"tools/filemanager";i:14;s:13:"tools/restore";}s:6:"modify";a:15:{i:0;s:14:"home/dashboard";i:1;s:11:"home/header";i:2;s:11:"home/logout";i:3;s:10:"home/reset";i:4;s:19:"kepegawaian/pegawai";i:5;s:10:"system/api";i:6;s:13:"system/banner";i:7;s:13:"system/layout";i:8;s:14:"system/setting";i:9;s:11:"system/user";i:10;s:17:"system/user_group";i:11;s:12:"tools/backup";i:12;s:15:"tools/error_log";i:13;s:17:"tools/filemanager";i:14;s:13:"tools/restore";}}'),
(2, 'Pegawai', '');


CREATE TABLE IF NOT EXISTS `ay_url_alias` (
  `url_alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`url_alias_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=852 ;


CREATE TABLE IF NOT EXISTS `ay_language` (
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
-- Dumping data untuk tabel `oc_language`
--

INSERT INTO `ay_language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `filename`, `sort_order`, `status`) VALUES
(1, 'Indonesia', 'id', 'id_ID.UTF-8,id_ID,id-id,indonesia', 'id.png', 'indonesia', 'indonesia', 1, 1);

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ay_tracker` (
`id` int(11) NOT NULL auto_increment,
`ip` text NOT NULL,
`country` text NOT NULL,
`city` text NOT NULL,
`query_string` text NOT NULL,
`http_referer` text NOT NULL,
`http_user_agent` text NOT NULL,
`isbot` int(11) NOT NULL,
`status` int(1) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `ay_tracker_detail` (
`id` int(11) NOT NULL auto_increment,
`tracker_id` int(11) NOT NULL,
`date` date NOT NULL,
`time` time NOT NULL,
`url` text NOT NULL,
`info` text NOT NULL,
PRIMARY KEY  (`id`)
);



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
