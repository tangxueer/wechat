-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2016 年 03 月 07 日 19:50
-- 服务器版本: 5.6.23
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_378711563`
--

-- --------------------------------------------------------

--
-- 表的结构 `operation`
--

CREATE TABLE IF NOT EXISTS `operation` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `step` varchar(30) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `operation`
--

INSERT INTO `operation` (`sid`, `fromusername`, `step`) VALUES
(5, 'oniFCwsio21aX84MqtlyX15lMLKU', 'sub'),
(4, 'oniFCwsio21aX84MqtlyX15lMLKU', 'sub');
