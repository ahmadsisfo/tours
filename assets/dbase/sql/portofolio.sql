-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 28. Februari 2016 jam 08:33
-- Versi Server: 5.5.16
-- Versi PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aypro`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `portfolio_article`
--

CREATE TABLE IF NOT EXISTS `portfolio_article` (
  `portfolio_article_id` int(16) NOT NULL AUTO_INCREMENT,
  `image` text NOT NULL,
  `featured_image` text NOT NULL,
  `client` text NOT NULL,
  `allow_urlpreview` tinyint(1) NOT NULL,
  `urlpreview` text NOT NULL,
  `article_related_method` varchar(64) NOT NULL,
  `article_related_option` text NOT NULL,
  `sort_order` int(8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`portfolio_article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `portfolio_article`
--

INSERT INTO `portfolio_article` (`portfolio_article_id`, `image`, `client`,`allow_urlpreview`, `urlpreview`, `featured_image`, `article_related_method`, `article_related_option`, `sort_order`, `status`, `date_added`, `date_modified`) VALUES
(1, 'catalog/demo/gift-voucher-birthday.jpg','mas hafiz','1','http://facebook.com','', 'product_wise', '', 0, 1, '2016-02-15 14:18:41', '2016-02-28 14:26:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `portfolio_article_description`
--

CREATE TABLE IF NOT EXISTS `portfolio_article_description` (
  `portfolio_article_description_id` int(16) NOT NULL AUTO_INCREMENT,
  `portfolio_article_id` int(16) NOT NULL,
  `language_id` int(16) NOT NULL,
  `article_title` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(256) NOT NULL,
  `meta_keyword` varchar(256) NOT NULL,
  PRIMARY KEY (`portfolio_article_description_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `portfolio_article_description`
--

INSERT INTO `portfolio_article_description` (`portfolio_article_description_id`, `portfolio_article_id`, `language_id`, `article_title`, `description`, `meta_description`, `meta_keyword`) VALUES
(3, 1, 2, 'tulisan blog 1', '&lt;p&gt;test ini adalah tulisan blog pertama&lt;/p&gt;', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `portfolio_article_to_category`
--

CREATE TABLE IF NOT EXISTS `portfolio_article_to_category` (
  `portfolio_article_id` int(16) NOT NULL,
  `portfolio_category_id` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `portfolio_article_to_category`
--

INSERT INTO `portfolio_article_to_category` (`portfolio_article_id`, `portfolio_category_id`) VALUES
(1, 1);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `portfolio_article_to_skill` (
  `portfolio_article_id` int(16) NOT NULL,
  `portfolio_skill_id` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `portfolio_skill` (
  `portfolio_skill_id` int(16) NOT NULL,
  `portfolio_skill_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Struktur dari tabel `portfolio_category`
--

CREATE TABLE IF NOT EXISTS `portfolio_category` (
  `portfolio_category_id` int(16) NOT NULL AUTO_INCREMENT,
  `image` text NOT NULL,
  `parent_id` int(16) NOT NULL,
  `top` tinyint(1) NOT NULL,
  `portfolio_category_column` int(16) NOT NULL,
  `column` int(8) NOT NULL,
  `sort_order` int(8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`portfolio_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `portfolio_category`
--

INSERT INTO `portfolio_category` (`portfolio_category_id`, `image`, `parent_id`, `top`, `portfolio_category_column`, `column`, `sort_order`, `status`, `date_added`, `date_modified`) VALUES
(1, '', 0, 0, 0, 10, 0, 1, '2016-02-15 14:13:48', '2016-02-15 14:13:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `portfolio_category_description`
--

CREATE TABLE IF NOT EXISTS `portfolio_category_description` (
  `portfolio_category_description_id` int(16) NOT NULL AUTO_INCREMENT,
  `portfolio_category_id` int(16) NOT NULL,
  `language_id` int(16) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(256) NOT NULL,
  `meta_keyword` varchar(256) NOT NULL,
  PRIMARY KEY (`portfolio_category_description_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `portfolio_category_description`
--

INSERT INTO `portfolio_category_description` (`portfolio_category_description_id`, `portfolio_category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES
(1, 1, 2, 'tulisan 1', '&lt;p&gt;example&lt;/p&gt;', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `portfolio_related_article`
--

CREATE TABLE IF NOT EXISTS `portfolio_related_article` (
  `portfolio_related_article_id` int(16) NOT NULL AUTO_INCREMENT,
  `portfolio_article_id` int(16) NOT NULL,
  `portfolio_article_related_id` int(16) NOT NULL,
  `sort_order` int(8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`portfolio_related_article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `portfolio_view`
--

CREATE TABLE IF NOT EXISTS `portfolio_view` (
  `portfolio_view_id` int(16) NOT NULL AUTO_INCREMENT,
  `portfolio_article_id` int(16) NOT NULL,
  `view` int(16) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`portfolio_view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `portfolio_view`
--

INSERT INTO `portfolio_view` (`portfolio_view_id`, `portfolio_article_id`, `view`, `date_added`, `date_modified`) VALUES
(1, 1, 25, '2016-02-15 14:41:21', '2016-02-28 14:29:53');

-- --------------------------------------------------------


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
