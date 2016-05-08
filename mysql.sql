-- phpMyAdmin SQL Dump
-- http://www.phpmyadmin.net
--
-- 生成日期: 2016 年 05 月 08 日 21:02

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `MrTkUtxTXZQQyVBUGHfn`
--

-- --------------------------------------------------------

--
-- 表的结构 `advice`
--

CREATE TABLE IF NOT EXISTS `advice` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `advice` text NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nickname` varchar(50) NOT NULL,
  `picurl` varchar(500) NOT NULL,
  `text` text NOT NULL,
  `picture` varchar(500) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=159 ;

-- --------------------------------------------------------

--
-- 表的结构 `operation`
--

CREATE TABLE IF NOT EXISTS `operation` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `step` varchar(30) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `operation`
--

INSERT INTO `operation` (`sid`, `fromusername`, `step`) VALUES
(23, 'oniFCwsio21aX84MqtlyX15lMLKU', 'sub');

-- --------------------------------------------------------

--
-- 表的结构 `wall`
--

CREATE TABLE IF NOT EXISTS `wall` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nickname` varchar(50) NOT NULL,
  `picurl` varchar(500) NOT NULL,
  `step` varchar(30) NOT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
