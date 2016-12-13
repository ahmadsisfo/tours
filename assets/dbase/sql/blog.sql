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
-- Struktur dari tabel `blog_article`
--

CREATE TABLE IF NOT EXISTS `blog_article` (
  `blog_article_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_author_id` int(16) NOT NULL,
  `allow_comment` tinyint(1) NOT NULL,
  `image` text NOT NULL,
  `featured_image` text NOT NULL,
  `article_related_method` varchar(64) NOT NULL,
  `article_related_option` text NOT NULL,
  `sort_order` int(8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`blog_article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `blog_article`
--

INSERT INTO `blog_article` (`blog_article_id`, `blog_author_id`, `allow_comment`, `image`, `featured_image`, `article_related_method`, `article_related_option`, `sort_order`, `status`, `date_added`, `date_modified`) VALUES
(1, 1, 1, '', 'catalog/demo/gift-voucher-birthday.jpg', 'product_wise', '', 0, 1, '2016-02-15 14:18:41', '2016-02-28 14:26:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_article_description`
--

CREATE TABLE IF NOT EXISTS `blog_article_description` (
  `blog_article_description_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_article_id` int(16) NOT NULL,
  `language_id` int(16) NOT NULL,
  `article_title` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(256) NOT NULL,
  `meta_keyword` varchar(256) NOT NULL,
  PRIMARY KEY (`blog_article_description_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `blog_article_description`
--

INSERT INTO `blog_article_description` (`blog_article_description_id`, `blog_article_id`, `language_id`, `article_title`, `description`, `meta_description`, `meta_keyword`) VALUES
(3, 1, 2, 'tulisan blog 1', '&lt;p&gt;test ini adalah tulisan blog pertama&lt;/p&gt;', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_article_description_additional`
--

CREATE TABLE IF NOT EXISTS `blog_article_description_additional` (
  `blog_article_id` int(16) NOT NULL,
  `language_id` int(16) NOT NULL,
  `additional_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `blog_article_description_additional`
--

INSERT INTO `blog_article_description_additional` (`blog_article_id`, `language_id`, `additional_description`) VALUES
(1, 2, '&lt;p&gt;&lt;br&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_article_product_related`
--

CREATE TABLE IF NOT EXISTS `blog_article_product_related` (
  `blog_article_id` int(16) NOT NULL,
  `product_id` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_article_to_category`
--

CREATE TABLE IF NOT EXISTS `blog_article_to_category` (
  `blog_article_id` int(16) NOT NULL,
  `blog_category_id` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `blog_article_to_category`
--

INSERT INTO `blog_article_to_category` (`blog_article_id`, `blog_category_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_author`
--

CREATE TABLE IF NOT EXISTS `blog_author` (
  `blog_author_id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`blog_author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `blog_author`
--

INSERT INTO `blog_author` (`blog_author_id`, `name`, `image`, `status`, `date_added`, `date_modified`) VALUES
(1, 'Administrator', '', 1, '2016-02-15 14:18:23', '2016-02-15 14:18:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_author_description`
--

CREATE TABLE IF NOT EXISTS `blog_author_description` (
  `blog_author_description_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_author_id` int(16) NOT NULL,
  `language_id` int(16) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(256) NOT NULL,
  `meta_keyword` varchar(256) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`blog_author_description_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `blog_author_description`
--

INSERT INTO `blog_author_description` (`blog_author_description_id`, `blog_author_id`, `language_id`, `description`, `meta_description`, `meta_keyword`, `date_added`) VALUES
(1, 1, 2, '&lt;p&gt;&lt;br&gt;&lt;/p&gt;', '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_category`
--

CREATE TABLE IF NOT EXISTS `blog_category` (
  `blog_category_id` int(16) NOT NULL AUTO_INCREMENT,
  `image` text NOT NULL,
  `parent_id` int(16) NOT NULL,
  `top` tinyint(1) NOT NULL,
  `blog_category_column` int(16) NOT NULL,
  `column` int(8) NOT NULL,
  `sort_order` int(8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`blog_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `blog_category`
--

INSERT INTO `blog_category` (`blog_category_id`, `image`, `parent_id`, `top`, `blog_category_column`, `column`, `sort_order`, `status`, `date_added`, `date_modified`) VALUES
(1, '', 0, 0, 0, 10, 0, 1, '2016-02-15 14:13:48', '2016-02-15 14:13:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_category_description`
--

CREATE TABLE IF NOT EXISTS `blog_category_description` (
  `blog_category_description_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_category_id` int(16) NOT NULL,
  `language_id` int(16) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(256) NOT NULL,
  `meta_keyword` varchar(256) NOT NULL,
  PRIMARY KEY (`blog_category_description_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `blog_category_description`
--

INSERT INTO `blog_category_description` (`blog_category_description_id`, `blog_category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES
(1, 1, 2, 'tulisan 1', '&lt;p&gt;example&lt;/p&gt;', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_comment`
--

CREATE TABLE IF NOT EXISTS `blog_comment` (
  `blog_comment_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_article_id` int(16) NOT NULL,
  `blog_article_reply_id` int(16) NOT NULL,
  `author` varchar(64) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`blog_comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data untuk tabel `blog_comment`
--

INSERT INTO `blog_comment` (`blog_comment_id`, `blog_article_id`, `blog_article_reply_id`, `author`, `comment`, `status`, `date_added`, `date_modified`) VALUES
(1, 1, 0, 'test', 'mantap juga nih', 1, '2016-02-15 14:42:07', '2016-02-15 14:44:33'),
(2, 1, 0, 'deded', 'edeed', 1, '2016-02-15 14:43:38', '2016-02-15 14:46:35'),
(4, 1, 2, 'dgdfg', 'dfdfgdf', 1, '2016-02-15 14:46:35', '2016-02-15 14:46:35'),
(5, 1, 0, 'nana', 'test mangap', 1, '2016-02-15 14:49:37', '2016-02-15 14:50:16'),
(6, 1, 0, 'asdasd', 'asdsad', 0, '2016-02-15 14:53:18', '2016-02-15 14:53:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_related_article`
--

CREATE TABLE IF NOT EXISTS `blog_related_article` (
  `blog_related_article_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_article_id` int(16) NOT NULL,
  `blog_article_related_id` int(16) NOT NULL,
  `sort_order` int(8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`blog_related_article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_view`
--

CREATE TABLE IF NOT EXISTS `blog_view` (
  `blog_view_id` int(16) NOT NULL AUTO_INCREMENT,
  `blog_article_id` int(16) NOT NULL,
  `view` int(16) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`blog_view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `blog_view`
--

INSERT INTO `blog_view` (`blog_view_id`, `blog_article_id`, `view`, `date_added`, `date_modified`) VALUES
(1, 1, 25, '2016-02-15 14:41:21', '2016-02-28 14:29:53');

-- --------------------------------------------------------


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
