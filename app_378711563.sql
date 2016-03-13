-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2016 年 03 月 13 日 20:52
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

INSERT INTO `advice` (`aid`, `fromusername`, `advice`) VALUES
(8, 'oniFCwsio21aX84MqtlyX15lMLKU', '测试');

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `picurl` varchar(300) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=106 ;

--
-- 转存表中的数据 `message`
--

INSERT INTO `message` (`mid`, `fromusername`, `date`, `text`, `picurl`) VALUES
(104, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-13 20:44:15', '', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjt0FLsiaAqicG3IXJIOfE7dsgzeRKkkFiaDO6vRRJ4esDd8A9ib0mFOiaHOLuNlB8Tvwa2Y6ejMPhHuUA/0'),
(96, 'oniFCwpcics0KQsp5XV69jLW0dPE', '2016-03-13 20:23:49', '', 'http://mmbiz.qpic.cn/mmbiz/dGibCuSp8oetNhrkVA8nu7XY4pjEy7BeIYgl4tPF143icsL2DjH9pVnf9m5Kia89ibmZTZQTwYCUZRLhXW0EBd0bww/0'),
(100, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-13 20:41:30', '测试成功', ''),
(101, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-13 20:43:36', '测测', ''),
(95, 'oniFCwpcics0KQsp5XV69jLW0dPE', '2016-03-13 20:23:31', '', 'http://mmbiz.qpic.cn/mmbiz/dGibCuSp8oetNhrkVA8nu7XY4pjEy7BeIvWo9vb8bRfQrIAUbVGw5d4EvutXTE3xjAYPergSsmR65op1Ol6Tjcw/0'),
(91, 'oniFCwnsaT6O6CL210T-8w4lsbBk', '2016-03-13 20:17:19', '我要改名字！', ''),
(85, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-13 20:04:59', '', 'http://mmbiz.qpic.cn/mmbiz/uGL4LniaJnsjt0FLsiaAqicG3IXJIOfE7dsicfLZpbSM8C8lmk75GrE8eF79fuss2wugIJVPys7Bl5FcphVX2krWWQ/0'),
(105, 'oniFCwsio21aX84MqtlyX15lMLKU', '2016-03-13 20:51:36', '提交', '');

-- --------------------------------------------------------

--
-- 表的结构 `operation`
--

CREATE TABLE IF NOT EXISTS `operation` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `fromusername` varchar(100) NOT NULL,
  `step` varchar(30) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `operation`
--

INSERT INTO `operation` (`sid`, `fromusername`, `step`) VALUES
(16, 'oniFCwsio21aX84MqtlyX15lMLKU', 'sub'),
(15, 'oniFCwpcics0KQsp5XV69jLW0dPE', 'wall'),
(14, 'oniFCwnsaT6O6CL210T-8w4lsbBk', 'adv'),
(13, 'oniFCwsio21aX84MqtlyX15lMLKU', 'sub');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `wall`
--

INSERT INTO `wall` (`wid`, `fromusername`, `date`, `nickname`, `picurl`, `step`) VALUES
(18, 'oniFCwpcics0KQsp5XV69jLW0dPE', '2016-03-13 20:22:19', '朴灿烈的女朋友', 'http://mmbiz.qpic.cn/mmbiz/dGibCuSp8oetNhrkVA8nu7XY4pjEy7BeIKHFGc4XnsIgpdLfdVnc4uibIOUibH7jNjMc11eibAiaYibQHp2xnlVDR6jA/0', 'onthewall');
