-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2016 年 03 月 14 日 14:54
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
-- 表的结构 `advice`
--

CREATE TABLE IF NOT EXISTS `advice` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `advice` text NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `advice`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

--
-- 转存表中的数据 `message`
--

INSERT INTO `message` (`mid`, `fromusername`, `date`, `nickname`, `picurl`, `text`, `picture`) VALUES
(133, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-14 14:38:48', '测试1.0', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9kuZ3sImprEJH7xOnGRbicLxg9hNvdWU2Xe4cOqntqSJIWXdemGyVCjy4Q/0', '测试1.0', ''),
(134, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-14 14:38:56', '测试1.0', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9kuZ3sImprEJH7xOnGRbicLxg9hNvdWU2Xe4cOqntqSJIWXdemGyVCjy4Q/0', '测试2.0', ''),
(135, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-14 14:39:19', '测试1.0', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9kuZ3sImprEJH7xOnGRbicLxg9hNvdWU2Xe4cOqntqSJIWXdemGyVCjy4Q/0', '', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9ku6RUZWPz5m7OHhjLsQXd9poFKlsc6iafR20DibR7DIgUBcibFg4Qag8nyA/0'),
(136, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-14 14:45:07', '测试1.0', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9kuZ3sImprEJH7xOnGRbicLxg9hNvdWU2Xe4cOqntqSJIWXdemGyVCjy4Q/0', '', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9ku6RUZWPz5m7OHhjLsQXd9poFKlsc6iafR20DibR7DIgUBcibFg4Qag8nyA/0'),
(137, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-14 14:45:26', '测试1.0', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9kuZ3sImprEJH7xOnGRbicLxg9hNvdWU2Xe4cOqntqSJIWXdemGyVCjy4Q/0', '', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjzg14v4ShkibjkfBPmzL9kuqmnDI0X9D1mwsELjVoV4N2xUXJtVnQWaCaTH70eT5x6Eo3JjY4Op6g/0');

-- --------------------------------------------------------

--
-- 表的结构 `operation`
--

CREATE TABLE IF NOT EXISTS `operation` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `step` varchar(30) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `operation`
--

INSERT INTO `operation` (`sid`, `fromusername`, `step`) VALUES
(22, 'oniFCwsio21aX84MqtlyX15lMLKU', 'sub');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `wall`
--

